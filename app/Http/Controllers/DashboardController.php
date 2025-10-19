<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('admin.dashboard', [
            'totalUsers' => User::where('role', 'user')->count(),
            'totalTasks' => Task::count(),
            'admins' => User::where('role', 'admin')->get(),
            'users' => User::where('role', 'user')->latest()->take(5)->get(),
        ]);
    }
}