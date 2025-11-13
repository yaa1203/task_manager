<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use App\Models\Workspace;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Tampilkan semua user untuk monitoring (per admin berdasarkan kategori yang sama)
     */
    public function index(Request $request)
    {
        $admin = Auth::user();
        $adminId = $admin->id;
        $adminCategoryId = $admin->category_id;
        
        $sortBy = $request->get('sort_by', 'created_at');
        $search = $request->get('search');

        $query = User::where('role', 'user')
            ->where('category_id', $adminCategoryId)
            ->withCount('assignedTasks');

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sorting dengan logika yang diperbaiki
        switch ($sortBy) {
            case 'most_diligent':
                $users = $this->getUsersWithImprovedScore($query, $adminId, 'desc');
                break;
            case 'least_diligent':
                $users = $this->getUsersWithImprovedScore($query, $adminId, 'asc');
                break;
            case 'most_completed':
                $users = $this->getUsersWithCompletionMetrics($query, $adminId, 'desc');
                break;
            case 'most_late':
                $users = $this->getUsersWithLateMetrics($query, $adminId, 'desc');
                break;
            case 'least_late':
                $users = $this->getUsersWithLateMetrics($query, $adminId, 'asc');
                break;
            case 'name':
                $users = $query->orderBy('name', 'asc')->paginate(15);
                break;
            case 'email':
                $users = $query->orderBy('email', 'asc')->paginate(15);
                break;
            default:
                $users = $query->orderBy('created_at', 'desc')->paginate(15);
        }

        $users->appends(['sort_by' => $sortBy, 'search' => $search]);

        // Ambil semua data submissions untuk users ini
        $userIds = $users->pluck('id');

        $submissionsData = DB::table('user_task_submissions')
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->join('workspaces', 'tasks.workspace_id', '=', 'workspaces.id')
            ->where('workspaces.admin_id', $adminId)
            ->where('workspaces.is_archived', false)
            ->whereIn('user_task_submissions.user_id', $userIds)
            ->select(
                'user_task_submissions.user_id',
                'user_task_submissions.created_at as submission_date',
                'tasks.due_date'
            )
            ->get()
            ->groupBy('user_id');

        $users->getCollection()->transform(function ($user) use ($submissionsData, $adminId) {
            $userSubmissions = $submissionsData->get($user->id, collect());

            $onTimeCount = $userSubmissions->filter(fn($s) => $s->submission_date <= $s->due_date)->count();
            $lateCount = $userSubmissions->filter(fn($s) => $s->submission_date > $s->due_date)->count();

            $user->diligence_score = $this->calculateImprovedScore($user, $adminId);
            $user->late_submissions_count = $lateCount;
            $user->on_time_submissions_count = $onTimeCount;
            $user->completion_rate = $this->calculateCompletionRate($user, $adminId);

            return $user;
        });

        return view('admin.users.index', compact('users', 'sortBy', 'search'));
    }

    /**
     * ✅ IMPROVED: Scoring dengan pertimbangan persentase dan total tugas
     * Formula: (completion_rate * 0.4) + (on_time_rate * 0.4) + (total_tasks * 0.2)
     */
    private function calculateImprovedScore($user, $adminId)
    {
        $totalAssigned = $user->assignedTasks()
            ->whereHas('workspace', fn($w) => $w->where('admin_id', $adminId)->where('is_archived', false))
            ->count();

        if ($totalAssigned === 0) return 0;

        // Hitung completion rate (0-100)
        $completed = $user->submissions()
            ->whereHas('task.workspace', fn($w) => $w->where('admin_id', $adminId)->where('is_archived', false))
            ->distinct('task_id')
            ->count();
        $completionRate = ($completed / $totalAssigned) * 100;

        // Hitung on-time rate (0-100)
        $onTime = $this->countOnTimeSubmissions($user, $adminId);
        $late = $this->countLateSubmissions($user, $adminId);
        $totalSubmitted = $onTime + $late;
        $onTimeRate = $totalSubmitted > 0 ? ($onTime / $totalSubmitted) * 100 : 0;

        // Normalize total tasks (max 100 untuk memberikan range yang sama)
        $normalizedTasks = min($totalAssigned, 100);

        // Formula weighted score
        $score = ($completionRate * 0.4) + ($onTimeRate * 0.4) + ($normalizedTasks * 0.2);

        return round($score, 2);
    }

    /**
     * ✅ IMPROVED: Sorting berdasarkan skor yang lebih akurat
     */
    private function getUsersWithImprovedScore($query, $adminId, $direction = 'desc')
    {
        $users = $query->get();

        $usersWithScore = $users->map(function ($user) use ($adminId) {
            $user->temp_improved_score = $this->calculateImprovedScore($user, $adminId);
            return $user;
        });

        $sorted = $direction === 'desc'
            ? $usersWithScore->sortByDesc('temp_improved_score')
            : $usersWithScore->sortBy('temp_improved_score');

        return $this->paginateCollection($sorted, 15);
    }

    /**
     * ✅ IMPROVED: Sorting berdasarkan completion rate dan jumlah
     */
    private function getUsersWithCompletionMetrics($query, $adminId, $direction = 'desc')
    {
        $users = $query->get();

        $usersWithMetrics = $users->map(function ($user) use ($adminId) {
            $totalAssigned = $user->assignedTasks()
                ->whereHas('workspace', fn($w) => $w->where('admin_id', $adminId)->where('is_archived', false))
                ->count();

            $completed = $user->submissions()
                ->whereHas('task.workspace', fn($w) => $w->where('admin_id', $adminId)->where('is_archived', false))
                ->distinct('task_id')
                ->count();

            $completionRate = $totalAssigned > 0 ? ($completed / $totalAssigned) * 100 : 0;

            // Score = (completion_rate * 0.7) + (completed_count * 0.3)
            $user->temp_completion_score = ($completionRate * 0.7) + ($completed * 0.3);
            $user->temp_completion_rate = $completionRate;
            $user->temp_completed_count = $completed;
            
            return $user;
        });

        $sorted = $direction === 'desc'
            ? $usersWithMetrics->sortByDesc(fn($u) => [$u->temp_completion_score, $u->temp_completed_count])
            : $usersWithMetrics->sortBy(fn($u) => [$u->temp_completion_score, $u->temp_completed_count]);

        return $this->paginateCollection($sorted, 15);
    }

    /**
     * ✅ IMPROVED: Sorting berdasarkan late rate dan jumlah
     */
    private function getUsersWithLateMetrics($query, $adminId, $direction = 'desc')
    {
        $users = $query->get();

        $usersWithMetrics = $users->map(function ($user) use ($adminId) {
            $onTime = $this->countOnTimeSubmissions($user, $adminId);
            $late = $this->countLateSubmissions($user, $adminId);
            $totalSubmitted = $onTime + $late;

            $lateRate = $totalSubmitted > 0 ? ($late / $totalSubmitted) * 100 : 0;

            // Score = (late_rate * 0.7) + (late_count * 0.3)
            $user->temp_late_score = ($lateRate * 0.7) + ($late * 0.3);
            $user->temp_late_rate = $lateRate;
            $user->temp_late_count = $late;
            
            return $user;
        });

        $sorted = $direction === 'desc'
            ? $usersWithMetrics->sortByDesc(fn($u) => [$u->temp_late_score, $u->temp_late_count])
            : $usersWithMetrics->sortBy(fn($u) => [$u->temp_late_score, $u->temp_late_count]);

        return $this->paginateCollection($sorted, 15);
    }

    /**
     * Pagination manual (untuk hasil koleksi sorting custom)
     */
    private function paginateCollection($collection, $perPage = 15)
    {
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $items = $collection->slice($offset, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $collection->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    private function countLateSubmissions($user, $adminId)
    {
        return DB::table('user_task_submissions')
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->join('workspaces', 'tasks.workspace_id', '=', 'workspaces.id')
            ->where('workspaces.admin_id', $adminId)
            ->where('workspaces.is_archived', false)
            ->where('user_task_submissions.user_id', $user->id)
            ->whereRaw('user_task_submissions.created_at > tasks.due_date')
            ->count();
    }

    private function countOnTimeSubmissions($user, $adminId)
    {
        return DB::table('user_task_submissions')
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->join('workspaces', 'tasks.workspace_id', '=', 'workspaces.id')
            ->where('workspaces.admin_id', $adminId)
            ->where('workspaces.is_archived', false)
            ->where('user_task_submissions.user_id', $user->id)
            ->whereRaw('user_task_submissions.created_at <= tasks.due_date')
            ->count();
    }

    private function calculateCompletionRate($user, $adminId)
    {
        $totalAssigned = $user->assignedTasks()
            ->whereHas('workspace', fn($w) => $w->where('admin_id', $adminId)->where('is_archived', false))
            ->count();

        if ($totalAssigned === 0) return 0;

        $completed = $user->submissions()
            ->whereHas('task.workspace', fn($w) => $w->where('admin_id', $adminId)->where('is_archived', false))
            ->distinct('task_id')
            ->count();

        return round(($completed / $totalAssigned) * 100, 1);
    }

    // ... rest of the methods remain the same ...
    
    public function destroy(User $user)
    {
        $admin = Auth::user();
        $adminId = $admin->id;
        $adminCategoryId = $admin->category_id;

        if ($adminId === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($user->category_id !== $adminCategoryId) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus user dari kategori lain.');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    public function show(User $user)
    {
        $admin = Auth::user();
        $adminId = $admin->id;
        $adminCategoryId = $admin->category_id;

        if ($user->category_id !== $adminCategoryId) {
            return redirect()->route('users.index')->with('error', 'User ini tidak termasuk dalam kategori Anda.');
        }

        $tasks = $user->assignedTasks()
            ->whereHas('workspace', function($w) use ($adminCategoryId) {
                $w->whereHas('admin', fn($a) => $a->where('category_id', $adminCategoryId));
            })
            ->with([
                'workspace.admin',
                'submissions' => fn($q) => $q->where('user_id', $user->id),
                'assignedUsers',
                'creator'
            ])
            ->latest()
            ->get();

        $activeTasks = $tasks->filter(fn($t) => !$t->workspace->is_archived);
        $archivedTasks = $tasks->filter(fn($t) => $t->workspace->is_archived);

        $totalTasks = $activeTasks->count();
        $completedTasks = $activeTasks->filter(fn($t) => $t->submissions->isNotEmpty())->count();
        $overdueTasks = $activeTasks->filter(function($t) {
            $hasSubmitted = $t->submissions->isNotEmpty();
            return !$hasSubmitted && $t->due_date && now()->gt($t->due_date);
        })->count();
        $unfinishedTasks = $activeTasks->filter(function($t) {
            $hasSubmitted = $t->submissions->isNotEmpty();
            return !$hasSubmitted && (!$t->due_date || now()->lte($t->due_date));
        })->count();

        $diligenceScore = $this->calculateImprovedScore($user, $adminId);
        $completionRate = $this->calculateCompletionRateAllAdmins($user, $adminCategoryId);

        return view('admin.users.show', compact(
            'user', 
            'tasks',
            'activeTasks',
            'archivedTasks',
            'totalTasks',
            'completedTasks', 
            'overdueTasks',
            'unfinishedTasks', 
            'diligenceScore', 
            'completionRate'
        ));
    }

    private function countOnTimeSubmissionsAllAdmins($user, $categoryId)
    {
        return DB::table('user_task_submissions')
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->join('workspaces', 'tasks.workspace_id', '=', 'workspaces.id')
            ->join('users as admins', 'workspaces.admin_id', '=', 'admins.id')
            ->where('admins.category_id', $categoryId)
            ->where('workspaces.is_archived', false)
            ->where('user_task_submissions.user_id', $user->id)
            ->whereRaw('user_task_submissions.created_at <= tasks.due_date')
            ->count();
    }

    private function countLateSubmissionsAllAdmins($user, $categoryId)
    {
        return DB::table('user_task_submissions')
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->join('workspaces', 'tasks.workspace_id', '=', 'workspaces.id')
            ->join('users as admins', 'workspaces.admin_id', '=', 'admins.id')
            ->where('admins.category_id', $categoryId)
            ->where('workspaces.is_archived', false)
            ->where('user_task_submissions.user_id', $user->id)
            ->whereRaw('user_task_submissions.created_at > tasks.due_date')
            ->count();
    }

    private function calculateCompletionRateAllAdmins($user, $categoryId)
    {
        $totalAssigned = $user->assignedTasks()
            ->whereHas('workspace', function($w) use ($categoryId) {
                $w->whereHas('admin', fn($a) => $a->where('category_id', $categoryId))
                ->where('is_archived', false);
            })
            ->count();

        if ($totalAssigned === 0) return 0;

        $completed = $user->submissions()
            ->whereHas('task.workspace', function($w) use ($categoryId) {
                $w->whereHas('admin', fn($a) => $a->where('category_id', $categoryId))
                ->where('is_archived', false);
            })
            ->distinct('task_id')
            ->count();

        return round(($completed / $totalAssigned) * 100, 1);
    }

    // Block/Unblock methods remain the same...
    public function block(User $user)
    {
        $admin = Auth::user();
        $adminCategoryId = $admin->category_id;

        if ($admin->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat memblokir akun sendiri.');
        }

        if ($user->category_id !== $adminCategoryId) {
            return back()->with('error', 'Anda tidak memiliki izin untuk memblokir user dari kategori lain.');
        }

        if ($user->is_blocked) {
            return back()->with('error', 'User ini sudah diblokir.');
        }

        $user->update([
            'is_blocked' => true,
            'blocked_at' => now(),
            'blocked_by' => $admin->id,
        ]);

        return back()->with('success', "Akun {$user->name} berhasil diblokir. User tidak dapat login ke sistem.");
    }

    public function unblock(User $user)
    {
        $admin = Auth::user();
        $adminCategoryId = $admin->category_id;

        if ($user->category_id !== $adminCategoryId) {
            return back()->with('error', 'Anda tidak memiliki izin untuk membuka blokir user dari kategori lain.');
        }

        if (!$user->is_blocked) {
            return back()->with('error', 'User ini tidak dalam status terblokir.');
        }

        $user->update([
            'is_blocked' => false,
            'blocked_at' => null,
            'blocked_by' => null,
        ]);

        return back()->with('success', "Akun {$user->name} berhasil dibuka blokirnya. User dapat login kembali.");
    }

    // SuperAdmin methods remain the same...
    public function superAdminIndex(Request $request)
    {
        $sortBy = $request->get('sort_by', 'created_at');
        $search = $request->get('search');
        $categoryFilter = $request->get('category_filter');

        $query = User::where('role', 'admin');

        if ($categoryFilter) {
            $query->where('category_id', $categoryFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        switch ($sortBy) {
            case 'name':
                $admins = $query->orderBy('name', 'asc')->paginate(15);
                break;
            case 'email':
                $admins = $query->orderBy('email', 'asc')->paginate(15);
                break;
            default:
                $admins = $query->orderBy('created_at', 'desc')->paginate(15);
        }

        $admins->appends(['sort_by' => $sortBy, 'search' => $search, 'category_filter' => $categoryFilter]);

        $categories = \App\Models\Category::all();

        return view('superadmin.pengguna.admin', compact('admins', 'sortBy', 'search', 'categoryFilter', 'categories'));
    }

    public function superAdminDestroy(User $user)
    {
        if ($user->role === 'superadmin') {
            return back()->with('error', 'Tidak dapat menghapus akun Super Admin.');
        }

        if ($user->role === 'user') {
            return back()->with('error', 'Tidak dapat menghapus user biasa dari halaman ini.');
        }

        $user->delete();
        return back()->with('success', 'Admin berhasil dihapus dari sistem.');
    }

    public function superAdminShow(User $user)
    {
        if ($user->role !== 'admin') {
            return redirect()->route('pengguna.admin')->with('error', 'Akses ditolak.');
        }

        $teamMembers = User::where('role', 'user')
            ->whereHas('assignedTasks', function($query) use ($user) {
                $query->where('tasks.admin_id', $user->id);
            })
            ->withCount(['assignedTasks as tasks_count' => function($query) use ($user) {
                $query->where('tasks.admin_id', $user->id);
            }])
            ->with(['assignedTasks' => function($query) use ($user) {
                $query->where('tasks.admin_id', $user->id)
                    ->oldest('tasks.created_at')
                    ->limit(1);
            }])
            ->get()
            ->map(function($member) {
                $firstTask = $member->assignedTasks->first();
                $member->first_assigned_at = $firstTask ? $firstTask->created_at : now();
                unset($member->assignedTasks);
                return $member;
            })
            ->sortBy('first_assigned_at');

        $totalTasks = Task::where('admin_id', $user->id)->count();
        $workspaceCount = Workspace::where('admin_id', $user->id)->count();

        return view('superadmin.pengguna.show', compact(
            'user', 
            'teamMembers', 
            'totalTasks', 
            'workspaceCount'
        ));
    }

    public function superUserIndex(Request $request)
    {
        $sortBy = $request->get('sort_by', 'created_at');
        $search = $request->get('search');
        $categoryFilter = $request->get('category_filter');

        $query = User::where('role', 'user');

        if ($categoryFilter) {
            $query->where('category_id', $categoryFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        switch ($sortBy) {
            case 'name':
                $users = $query->orderBy('name', 'asc')->paginate(15);
                break;
            case 'email':
                $users = $query->orderBy('email', 'asc')->paginate(15);
                break;
            default:
                $users = $query->orderBy('created_at', 'desc')->paginate(15);
        }

        $users->appends(['sort_by' => $sortBy, 'search' => $search, 'category_filter' => $categoryFilter]);

        $categories = Category::all();

        return view('superadmin.pengguna.user', compact('users', 'sortBy', 'search', 'categoryFilter', 'categories'));
    }

    public function superUserDestroy(User $user)
    {
        if ($user->role === 'superadmin') {
            return back()->with('error', 'Tidak dapat menghapus akun Super Admin.');
        }

        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat menghapus admin dari halaman ini.');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus dari sistem.');
    }

    public function superAdminBlock(User $user)
    {
        if ($user->role === 'superadmin') {
            return back()->with('error', 'Tidak dapat memblokir akun Super Admin.');
        }

        if ($user->is_blocked) {
            return back()->with('error', 'Admin ini sudah diblokir.');
        }

        $user->update([
            'is_blocked' => true,
            'blocked_at' => now(),
            'blocked_by' => Auth::id(),
        ]);

        return back()->with('success', "Akun admin {$user->name} berhasil diblokir.");
    }

    public function superAdminUnblock(User $user)
    {
        if (!$user->is_blocked) {
            return back()->with('error', 'Admin ini tidak dalam status terblokir.');
        }

        $user->update([
            'is_blocked' => false,
            'blocked_at' => null,
            'blocked_by' => null,
        ]);

        return back()->with('success', "Akun admin {$user->name} berhasil dibuka blokirnya.");
    }

    public function superUserBlock(User $user)
    {
        if (in_array($user->role, ['superadmin', 'admin'])) {
            return back()->with('error', 'Tidak dapat memblokir akun ini dari halaman user.');
        }

        if ($user->is_blocked) {
            return back()->with('error', 'User ini sudah diblokir.');
        }

        $user->update([
            'is_blocked' => true,
            'blocked_at' => now(),
            'blocked_by' => Auth::id(),
        ]);

        return back()->with('success', "Akun user {$user->name} berhasil diblokir.");
    }

    public function superUserUnblock(User $user)
    {
        if (!$user->is_blocked) {
            return back()->with('error', 'User ini tidak dalam status terblokir.');
        }

        $user->update([
            'is_blocked' => false,
            'blocked_at' => null,
            'blocked_by' => null,
        ]);

        return back()->with('success', "Akun user {$user->name} berhasil dibuka blokirnya.");
    }
}