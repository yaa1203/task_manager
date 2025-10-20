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
        })->with(['tasks' => function($q) use ($userId) {
            $q->whereHas('assignedUsers', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })->with(['submissions' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }]);
        }])->get();

        // Kumpulkan semua tasks user
        $allMyTasks = collect();
        foreach ($workspaces as $workspace) {
            $allMyTasks = $allMyTasks->merge($workspace->tasks);
        }

        $now = Carbon::now();

        // Hitung overdue tasks (belum submit & melewati deadline)
        $overdueTasks = $allMyTasks->filter(function($task) use ($now) {
            $hasSubmission = $task->submissions->isNotEmpty();
            if (!$hasSubmission && $task->due_date) {
                return Carbon::parse($task->due_date)->lt($now);
            }
            return false;
        });

        // Hitung tasks yang akan deadline dalam 24 jam ke depan (warning)
        // Task harus: belum disubmit, punya due_date, belum overdue, dan deadline-nya dalam 24 jam
        $upcomingDeadlineTasks = $allMyTasks->filter(function($task) use ($now) {
            $hasSubmission = $task->submissions->isNotEmpty();
            if (!$hasSubmission && $task->due_date) {
                $dueDate = Carbon::parse($task->due_date);
                
                // Deadline harus di masa depan (belum lewat)
                // Dan selisihnya maksimal 24 jam dari sekarang
                return $dueDate->gt($now) && $dueDate->lte($now->copy()->addHours(24));
            }
            return false;
        });

        // Statistik
        $totalTasks = $allMyTasks->count();
        $doneTasks = $allMyTasks->filter(function($task) {
            return $task->submissions->isNotEmpty();
        })->count();
        
        $overdueCount = $overdueTasks->count();
        $unfinishedTasks = $totalTasks - $doneTasks - $overdueCount;
        
        $completionRate = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;
        $workspacesCount = $workspaces->count();

        // Notifikasi terbaru
        $notifications = Auth::user()->notifications()->latest()->take(5)->get();

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
            'notifications'
        ));
    }

    public function AdminIndex()
    {
        // Ambil hanya user dengan role 'user' yang pernah di-assign ke task
        $users = User::where('role', 'user')
            ->whereHas('assignedTasks')
            ->withCount('assignedTasks')
            ->latest()
            ->take(5)
            ->get();

        // Hitung statistik untuk setiap user (konsisten dengan UserController)
        $users->each(function ($user) {
            $user->diligence_score = $this->calculateDiligenceScore($user);
            $user->late_submissions_count = $this->countLateSubmissions($user);
            $user->on_time_submissions_count = $this->countOnTimeSubmissions($user);
            $user->completion_rate = $this->calculateCompletionRate($user);
        });

        // Total users yang pernah diberi tugas
        $totalUsers = User::where('role', 'user')
            ->whereHas('assignedTasks')
            ->count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalTasks' => Task::count(),
            'users' => $users,
        ]);
    }

    /**
     * Calculate diligence score for a user
     */
    private function calculateDiligenceScore($user)
    {
        $onTimeCount = $this->countOnTimeSubmissions($user);
        $lateCount = $this->countLateSubmissions($user);
        return max(0, ($onTimeCount * 10) - ($lateCount * 5));
    }

    /**
     * Count late submissions for a user
     */
    private function countLateSubmissions($user)
    {
        return DB::table('user_task_submissions')
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->where('user_task_submissions.user_id', $user->id)
            ->whereRaw('user_task_submissions.created_at > tasks.due_date')
            ->count();
    }

    /**
     * Count on-time submissions for a user
     */
    private function countOnTimeSubmissions($user)
    {
        return DB::table('user_task_submissions')
            ->join('tasks', 'user_task_submissions.task_id', '=', 'tasks.id')
            ->where('user_task_submissions.user_id', $user->id)
            ->whereRaw('user_task_submissions.created_at <= tasks.due_date')
            ->count();
    }

    /**
     * Calculate completion rate percentage
     */
    private function calculateCompletionRate($user)
    {
        $totalAssigned = $user->assignedTasks()->count();
        if ($totalAssigned === 0) {
            return 0;
        }
        
        $completed = $user->submissions()->distinct('task_id')->count();
        return round(($completed / $totalAssigned) * 100, 1);
    }
}