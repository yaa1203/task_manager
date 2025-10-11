<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    // === Analytics untuk user ===
    
    /**
     * Display analytics dashboard for user
     */
    public function index()
    {
        return view('analytics.index');
    }

    /**
     * Get analytics data for user
     */
    public function data()
    {
        $userId = Auth::id();

        // Ambil semua tasks dari workspace yang di-assign ke user
        $allMyTasks = collect();
        $workspaces = Workspace::whereHas('tasks.assignedUsers', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with(['tasks' => function($q) use ($userId) {
            $q->whereHas('assignedUsers', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })->with(['submissions' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }]);
        }])->get();

        // Workspace breakdown data
        $workspaceBreakdown = [];
        foreach ($workspaces as $workspace) {
            $taskCount = $workspace->tasks->count();
            if ($taskCount > 0) {
                $workspaceBreakdown[] = [
                    'name' => $workspace->name,
                    'tasks' => $taskCount,
                    'icon' => $workspace->icon,
                    'color' => $workspace->color
                ];
            }
            $allMyTasks = $allMyTasks->merge($workspace->tasks);
        }

        // Hitung statistik tasks berdasarkan submissions
        $totalTasks = $allMyTasks->count();
        
        $doneTasks = $allMyTasks->filter(function($task) {
            return $task->submissions->isNotEmpty();
        })->count();
        
        $overdueTasks = $allMyTasks->filter(function($task) {
            $hasSubmission = $task->submissions->isNotEmpty();
            if (!$hasSubmission && $task->due_date) {
                return Carbon::parse($task->due_date)->isPast();
            }
            return false;
        })->count();
        
        $unfinishedTasks = $totalTasks - $doneTasks - $overdueTasks;

        $taskStats = [
            'done' => $doneTasks,
            'unfinished' => $unfinishedTasks,
            'overdue' => $overdueTasks,
        ];

        // Weekly trend data (last 7 days) - tasks completed berdasarkan submission date
        $weeklyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Count tasks yang di-submit pada tanggal tersebut
            $count = $allMyTasks->filter(function($task) use ($date) {
                return $task->submissions->filter(function($submission) use ($date) {
                    return $submission->submitted_at && Carbon::parse($submission->submitted_at)->isSameDay($date);
                })->isNotEmpty();
            })->count();
            
            $weeklyTrend[] = [
                'date' => $date->format('D'),
                'count' => $count
            ];
        }

        // Calculate completion rate
        $completionRate = $totalTasks > 0 
            ? round(($doneTasks / $totalTasks) * 100, 1)
            : 0;

        return response()->json([
            'tasks' => $taskStats,
            'workspaces' => [
                'total' => $workspaces->count(),
                'breakdown' => $workspaceBreakdown,
            ],
            'weekly_trend' => $weeklyTrend,
            'summary' => [
                'total_tasks' => $totalTasks,
                'completion_rate' => $completionRate,
            ]
        ]);
    }

    // === Analytics Global untuk Admin ===
    
    /**
     * Display analytics dashboard for admin
     */
    public function adminIndex()
    {
        return view('admin.analyticts.index');
    }

    /**
     * Get analytics data for admin (all users)
     */
    public function adminData()
    {
        // Ambil semua workspaces dengan tasks dan submissions
        $allWorkspaces = Workspace::with(['tasks.submissions', 'tasks.assignedUsers'])->get();
        
        $allTasks = collect();
        $workspaceStats = [];
        
        foreach ($allWorkspaces as $workspace) {
            $taskCount = $workspace->tasks->count();
            if ($taskCount > 0) {
                $workspaceStats[] = [
                    'name' => $workspace->name,
                    'tasks' => $taskCount,
                    'icon' => $workspace->icon,
                ];
            }
            $allTasks = $allTasks->merge($workspace->tasks);
        }

        // Hitung statistik tasks
        $totalTasks = $allTasks->count();
        
        $doneTasks = $allTasks->filter(function($task) {
            return $task->submissions->isNotEmpty();
        })->count();
        
        $overdueTasks = $allTasks->filter(function($task) {
            $hasAnySubmission = $task->submissions->isNotEmpty();
            if (!$hasAnySubmission && $task->due_date) {
                return Carbon::parse($task->due_date)->isPast();
            }
            return false;
        })->count();
        
        $unfinishedTasks = $totalTasks - $doneTasks - $overdueTasks;

        $taskStats = [
            'done' => $doneTasks,
            'unfinished' => $unfinishedTasks,
            'overdue' => $overdueTasks,
        ];

        // User statistics
        $totalUsers = User::where('role', 'user')->count();
        
        // Active users = users yang punya submission dalam 7 hari terakhir
        $activeUserIds = collect();
        foreach ($allTasks as $task) {
            $recentSubmissions = $task->submissions->filter(function($submission) {
                return $submission->submitted_at && 
                       Carbon::parse($submission->submitted_at)->gte(Carbon::now()->subDays(7));
            });
            foreach ($recentSubmissions as $submission) {
                $activeUserIds->push($submission->user_id);
            }
        }
        $activeUsers = $activeUserIds->unique()->count();

        // Weekly trend data (last 7 days)
        $weeklyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            $count = $allTasks->filter(function($task) use ($date) {
                return $task->submissions->filter(function($submission) use ($date) {
                    return $submission->submitted_at && 
                           Carbon::parse($submission->submitted_at)->isSameDay($date);
                })->isNotEmpty();
            })->count();
            
            $weeklyTrend[] = [
                'date' => $date->format('D'),
                'count' => $count
            ];
        }

        $completionRate = $totalTasks > 0 
            ? round(($doneTasks / $totalTasks) * 100, 1)
            : 0;

        return response()->json([
            'tasks' => $taskStats,
            'workspaces' => [
                'total' => $allWorkspaces->count(),
                'breakdown' => $workspaceStats,
            ],
            'users' => [
                'total' => $totalUsers,
                'active' => $activeUsers,
            ],
            'weekly_trend' => $weeklyTrend,
            'summary' => [
                'total_tasks' => $totalTasks,
                'completion_rate' => $completionRate,
            ]
        ]);
    }
}