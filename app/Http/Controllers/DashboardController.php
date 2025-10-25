<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Ambil semua workspace yang user terlibat di dalamnya
        $workspaces = Workspace::whereHas('tasks.assignedUsers', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
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

    public function AdminIndex()
    {
        $admin = Auth::user();
        $adminId = $admin->id;
        $adminCategoryId = $admin->category_id;
        $category = $admin->category->name ?? 'Admin';

        // ✅ Ambil hanya user dengan kategori yang sama dengan admin ini
        $users = User::where('role', 'user')
            ->where('category_id', $adminCategoryId) // Filter berdasarkan kategori
            ->withCount(['assignedTasks' => function ($q) use ($adminId) {
                $q->whereHas('workspace', function ($w) use ($adminId) {
                    $w->where('admin_id', $adminId);
                });
            }])
            ->latest()
            ->take(5)
            ->get();

        // Hitung statistik tiap user (spesifik milik admin ini)
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

        $totalTasks = Task::whereHas('workspace', function ($w) use ($adminId) {
            $w->where('admin_id', $adminId);
        })->count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalTasks' => $totalTasks,
            'users' => $users,
            'category' => $category,
        ]);
    }

    private function calculateDiligenceScore($user, $adminId)
    {
        $onTime = $this->countOnTimeSubmissions($user, $adminId);
        $late = $this->countLateSubmissions($user, $adminId);
        return max(0, ($onTime * 10) - ($late * 5));
    }

    private function countLateSubmissions($user, $adminId)
    {
        return DB::table('user_task_submissions')
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->join('workspaces', 'tasks.workspace_id', '=', 'workspaces.id')
            ->where('workspaces.admin_id', $adminId)
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
            ->where('user_task_submissions.user_id', $user->id)
            ->whereRaw('user_task_submissions.created_at <= tasks.due_date')
            ->count();
    }

    private function calculateCompletionRate($user, $adminId)
    {
        $totalAssigned = $user->assignedTasks()
            ->whereHas('workspace', fn($w) => $w->where('admin_id', $adminId))
            ->count();

        if ($totalAssigned === 0) return 0;

        $completed = $user->submissions()
            ->whereHas('task.workspace', fn($w) => $w->where('admin_id', $adminId))
            ->distinct('task_id')
            ->count();

        return round(($completed / $totalAssigned) * 100, 1);
    }

    public function superAdminDashboard()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalSuperAdmins = User::where('role', 'superadmin')->count();
        $totalWorkspaces = Workspace::count();

        $recentUsers = User::latest()->take(5)->get();

        $completedTasks = [
            'users' => User::where('role', 'user')->get()->sum->completed_tasks_count,
            'admins' => User::where('role', 'admin')->get()->sum->completed_tasks_count,
            'superadmins' => User::where('role', 'superadmin')->get()->sum->completed_tasks_count,
        ];

        return view('superadmin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalSuperAdmins',
            'totalWorkspaces',
            'recentUsers',
            'completedTasks'
        ));
    }
}
