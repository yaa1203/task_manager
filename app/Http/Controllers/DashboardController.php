<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * User Dashboard
     * FIXED: Filter archived workspaces
     */
    public function index()
    {
        $userId = Auth::id();

        // ✅ PERBAIKAN: Hanya ambil workspace yang TIDAK diarsipkan
        $workspaces = Workspace::whereHas('tasks.assignedUsers', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->where('is_archived', false) // ⚠️ CRITICAL: Filter workspace yang diarsipkan
        ->with(['tasks' => function($q) use ($userId) {
            $q->whereHas('assignedUsers', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })->with(['submissions' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }]);
        }])
        ->get();

        $allMyTasks = collect();
        foreach ($workspaces as $workspace) {
            $allMyTasks = $allMyTasks->merge($workspace->tasks);
        }

        $now = Carbon::now();

        $overdueTasks = $allMyTasks->filter(function($task) use ($now) {
            $hasSubmission = $task->submissions->isNotEmpty();
            return !$hasSubmission && $task->due_date && Carbon::parse($task->due_date)->lt($now);
        });

        $upcomingDeadlineTasks = $allMyTasks->filter(function($task) use ($now) {
            $hasSubmission = $task->submissions->isNotEmpty();
            if (!$hasSubmission && $task->due_date) {
                $dueDate = Carbon::parse($task->due_date);
                return $dueDate->gt($now) && $dueDate->lte($now->copy()->addHours(24));
            }
            return false;
        });

        $totalTasks = $allMyTasks->count();
        $doneTasks = $allMyTasks->filter(fn($task) => $task->submissions->isNotEmpty())->count();
        $overdueCount = $overdueTasks->count();
        $unfinishedTasks = $totalTasks - $doneTasks - $overdueCount;
        $completionRate = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;
        $workspacesCount = $workspaces->count();

        $notifications = Auth::user()->notifications()->latest()->take(5)->get();

        $user = auth()->user();
        $category = $user->category ? $user->category->name : 'Belum memilih kategori';

        return view('dashboard', compact(
            'workspaces',
            'totalTasks',
            'doneTasks',
            'overdueCount',
            'unfinishedTasks',
            'completionRate',
            'workspacesCount',
            'overdueTasks',
            'upcomingDeadlineTasks',
            'notifications',
            'category',
            'user'
        ));
    }

    /**
     * Admin Dashboard
     * FIXED: Filter archived workspaces
     */
    public function AdminIndex()
    {
        $admin = Auth::user();
        $adminId = $admin->id;
        $adminCategoryId = $admin->category_id;
        $category = $admin->category->name ?? 'Admin';

        // ✅ Ambil hanya user dengan kategori yang sama dengan admin ini
        // ✅ PERBAIKAN: Hitung tasks hanya dari workspace yang TIDAK diarsipkan
        $users = User::where('role', 'user')
            ->where('category_id', $adminCategoryId)
            ->withCount(['assignedTasks' => function ($q) use ($adminId) {
                $q->whereHas('workspace', function ($w) use ($adminId) {
                    $w->where('admin_id', $adminId)
                      ->where('is_archived', false); // ⚠️ CRITICAL: Filter workspace yang diarsipkan
                });
            }])
            ->latest()
            ->take(6)
            ->get();

        // Hitung statistik tiap user (hanya dari workspace aktif)
        $users->each(function ($user) use ($adminId) {
            $user->diligence_score = $this->calculateDiligenceScore($user, $adminId);
            $user->late_submissions_count = $this->countLateSubmissions($user, $adminId);
            $user->on_time_submissions_count = $this->countOnTimeSubmissions($user, $adminId);
            $user->completion_rate = $this->calculateCompletionRate($user, $adminId);
        });

        // ✅ Total user dengan kategori yang sama
        $totalUsers = User::where('role', 'user')
            ->where('category_id', $adminCategoryId)
            ->count();

        // ✅ PERBAIKAN: Total tasks milik admin ini (hanya dari workspace yang TIDAK diarsipkan)
        $totalTasks = Task::whereHas('workspace', function ($w) use ($adminId) {
            $w->where('admin_id', $adminId)
              ->where('is_archived', false); // ⚠️ CRITICAL: Filter workspace yang diarsipkan
        })->count();

        // ✅ PERBAIKAN: Total workspaces milik admin ini (hanya yang TIDAK diarsipkan)
        $totalWorkspaces = Workspace::where('admin_id', $adminId)
            ->where('is_archived', false) // ⚠️ CRITICAL: Filter workspace yang diarsipkan
            ->count();

        // ✅ PERBAIKAN: Hitung status tugas untuk chart (hanya tugas dari workspace yang TIDAK diarsipkan)
        $now = Carbon::now();
        
        $completedTasks = Task::whereHas('workspace', function ($w) use ($adminId) {
            $w->where('admin_id', $adminId)
              ->where('is_archived', false); // ⚠️ CRITICAL: Filter workspace yang diarsipkan
        })
        ->whereHas('submissions')
        ->distinct('id')
        ->count();
        
        $overdueTasks = Task::whereHas('workspace', function ($w) use ($adminId) {
            $w->where('admin_id', $adminId)
              ->where('is_archived', false); // ⚠️ CRITICAL: Filter workspace yang diarsipkan
        })
        ->whereDoesntHave('submissions')
        ->whereNotNull('due_date')
        ->where('due_date', '<', $now)
        ->count();
        
        $pendingTasks = Task::whereHas('workspace', function ($w) use ($adminId) {
            $w->where('admin_id', $adminId)
              ->where('is_archived', false); // ⚠️ CRITICAL: Filter workspace yang diarsipkan
        })
        ->whereDoesntHave('submissions')
        ->where(function($q) use ($now) {
            $q->whereNull('due_date')
              ->orWhere('due_date', '>=', $now);
        })
        ->count();

        // ✅ Aktivitas terbaru (user yang baru bergabung di kategori ini)
        $recentUsers = User::where('role', 'user')
            ->where('category_id', $adminCategoryId)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalTasks' => $totalTasks,
            'totalWorkspaces' => $totalWorkspaces,
            'users' => $users,
            'category' => $category,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
            'overdueTasks' => $overdueTasks,
            'recentUsers' => $recentUsers,
        ]);
    }

    /**
     * Calculate diligence score
     * FIXED: Only count from non-archived workspaces
     */
    private function calculateDiligenceScore($user, $adminId)
    {
        $onTime = $this->countOnTimeSubmissions($user, $adminId);
        $late = $this->countLateSubmissions($user, $adminId);
        return max(0, ($onTime * 10) - ($late * 5));
    }

    /**
     * Count late submissions
     * FIXED: Only count from non-archived workspaces
     */
    private function countLateSubmissions($user, $adminId)
    {
        return DB::table('user_task_submissions')
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->join('workspaces', 'tasks.workspace_id', '=', 'workspaces.id')
            ->where('workspaces.admin_id', $adminId)
            ->where('workspaces.is_archived', false) // ⚠️ CRITICAL: Filter workspace yang diarsipkan
            ->where('user_task_submissions.user_id', $user->id)
            ->whereRaw('user_task_submissions.created_at > tasks.due_date')
            ->count();
    }

    /**
     * Count on-time submissions
     * FIXED: Only count from non-archived workspaces
     */
    private function countOnTimeSubmissions($user, $adminId)
    {
        return DB::table('user_task_submissions')
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->join('workspaces', 'tasks.workspace_id', '=', 'workspaces.id')
            ->where('workspaces.admin_id', $adminId)
            ->where('workspaces.is_archived', false) // ⚠️ CRITICAL: Filter workspace yang diarsipkan
            ->where('user_task_submissions.user_id', $user->id)
            ->whereRaw('user_task_submissions.created_at <= tasks.due_date')
            ->count();
    }

    /**
     * Calculate completion rate
     * FIXED: Only count from non-archived workspaces
     */
    private function calculateCompletionRate($user, $adminId)
    {
        $totalAssigned = $user->assignedTasks()
            ->whereHas('workspace', function($w) use ($adminId) {
                $w->where('admin_id', $adminId)
                  ->where('is_archived', false); // ⚠️ CRITICAL: Filter workspace yang diarsipkan
            })
            ->count();

        if ($totalAssigned === 0) return 0;

        $completed = $user->submissions()
            ->whereHas('task.workspace', function($w) use ($adminId) {
                $w->where('admin_id', $adminId)
                  ->where('is_archived', false); // ⚠️ CRITICAL: Filter workspace yang diarsipkan
            })
            ->distinct('task_id')
            ->count();

        return round(($completed / $totalAssigned) * 100, 1);
    }

    /**
     * SuperAdmin Dashboard
     * FIXED: Filter archived workspaces
     */
    public function superAdminDashboard()
    {
        // ✅ Hanya user biasa (role = 'user')
        $totalUsers = User::where('role', 'user')->count();
        
        // ✅ Hanya admin biasa (role = 'admin')
        $totalAdmins = User::where('role', 'admin')->count();
        
        // ✅ PERBAIKAN: Hitung total workspace (hanya yang TIDAK diarsipkan)
        $totalWorkspaces = Workspace::where('is_archived', false)->count();
        
        // Hitung total kategori
        $totalCategories = Category::count();
        
        // ✅ Ambil user terbaru (HANYA user dan admin, TIDAK termasuk superadmin)
        $recentUsers = User::whereIn('role', ['user', 'admin'])
            ->latest()
            ->take(6)
            ->get();
        
        // ✅ PERBAIKAN: HITUNG STATUS TUGAS UNTUK CHART (HANYA dari workspace yang TIDAK diarsipkan)
        $now = Carbon::now();
        
        $completedTasks = Task::whereHas('workspace', function($w) {
            $w->where('is_archived', false); // ⚠️ CRITICAL: Filter workspace yang diarsipkan
        })
        ->whereHas('submissions')
        ->distinct('id')
        ->count();
        
        $overdueTasks = Task::whereHas('workspace', function($w) {
            $w->where('is_archived', false); // ⚠️ CRITICAL: Filter workspace yang diarsipkan
        })
        ->whereDoesntHave('submissions')
        ->whereNotNull('due_date')
        ->where('due_date', '<', $now)
        ->count();
        
        $pendingTasks = Task::whereHas('workspace', function($w) {
            $w->where('is_archived', false); // ⚠️ CRITICAL: Filter workspace yang diarsipkan
        })
        ->whereDoesntHave('submissions')
        ->where(function($q) use ($now) {
            $q->whereNull('due_date')
              ->orWhere('due_date', '>=', $now);
        })
        ->count();
        
        return view('superadmin.dashboard', compact(
            'totalAdmins',
            'totalUsers',
            'totalWorkspaces',
            'totalCategories',
            'recentUsers',
            'completedTasks',
            'pendingTasks',
            'overdueTasks'
        ));
    }
}