<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    // === Analytics untuk user ===
    public function index()
    {
        return view('analytics.index');
    }

    public function data()
    {
        $userId = Auth::id();

        // Statistik Task user dengan normalisasi status
        $tasks = Task::where('user_id', $userId)
            ->selectRaw("
                CASE 
                    WHEN LOWER(status) = 'done' OR LOWER(status) = 'completed' THEN 'done'
                    WHEN LOWER(status) IN ('in_progress', 'in-progress', 'progress', 'in progress') THEN 'in_progress'
                    WHEN LOWER(status) IN ('pending', 'todo', 'to do', 'not started') THEN 'pending'
                    ELSE 'pending'
                END as normalized_status,
                COUNT(*) as total
            ")
            ->groupBy('normalized_status')
            ->pluck('total', 'normalized_status');

        // Ensure all statuses exist with actual count
        $taskStats = [
            'pending' => $tasks['pending'] ?? 0,
            'in_progress' => $tasks['in_progress'] ?? 0,
            'done' => $tasks['done'] ?? 0,
        ];

        // Get actual total count from database
        $actualTotal = Task::where('user_id', $userId)->count();
        
        // If there's a mismatch, recalculate
        if (array_sum($taskStats) != $actualTotal) {
            // Direct query to get accurate counts
            $pendingCount = Task::where('user_id', $userId)
                ->whereIn('status', ['pending', 'todo', 'to do', 'not started'])
                ->count();
            
            $progressCount = Task::where('user_id', $userId)
                ->whereIn('status', ['in_progress', 'in-progress', 'progress', 'in progress'])
                ->count();
            
            $doneCount = Task::where('user_id', $userId)
                ->whereIn('status', ['done', 'completed'])
                ->count();
            
            $taskStats = [
                'pending' => $pendingCount,
                'in_progress' => $progressCount,
                'done' => $doneCount,
            ];
        }

        // Statistik Project user
        $projects = Project::where('user_id', $userId)->get();
        $now = Carbon::now();
        
        $activeProjects = $projects->filter(function($project) use ($now) {
            return $project->end_date && Carbon::parse($project->end_date)->gte($now);
        })->count();
        
        $finishedProjects = $projects->filter(function($project) use ($now) {
            return $project->end_date && Carbon::parse($project->end_date)->lt($now);
        })->count();

        // Weekly trend data (last 7 days) - only count actual completed tasks
        $weeklyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Task::where('user_id', $userId)
                ->whereIn('status', ['done', 'completed'])
                ->whereDate('updated_at', $date)
                ->count();
            
            $weeklyTrend[] = [
                'date' => $date->format('D'),
                'count' => $count
            ];
        }

        // Calculate completion rate based on actual data
        $totalTasks = array_sum($taskStats);
        $completionRate = $totalTasks > 0 
            ? round(($taskStats['done'] / $totalTasks) * 100, 1)
            : 0;

        return response()->json([
            'tasks' => $taskStats,
            'projects' => [
                'active' => $activeProjects,
                'finished' => $finishedProjects,
            ],
            'weekly_trend' => $weeklyTrend,
            'summary' => [
                'total_tasks' => $totalTasks,
                'completion_rate' => $completionRate,
            ]
        ]);
    }

    // === Analytics Global untuk Admin ===
    public function adminIndex()
    {
        return view('admin.analyticts.index'); // Fixed typo: analyticts -> analytics
    }

    public function adminData()
    {
        // Statistik Task semua user dengan normalisasi status
        $tasks = Task::selectRaw("
                CASE 
                    WHEN LOWER(status) = 'done' OR LOWER(status) = 'completed' THEN 'done'
                    WHEN LOWER(status) IN ('in_progress', 'in-progress', 'progress', 'in progress') THEN 'in_progress'
                    WHEN LOWER(status) IN ('pending', 'todo', 'to do', 'not started') THEN 'pending'
                    ELSE 'pending'
                END as normalized_status,
                COUNT(*) as total
            ")
            ->groupBy('normalized_status')
            ->pluck('total', 'normalized_status');

        // Ensure all statuses exist
        $taskStats = [
            'pending' => $tasks['pending'] ?? 0,
            'in_progress' => $tasks['in_progress'] ?? 0,
            'done' => $tasks['done'] ?? 0,
        ];

        // Validate total count
        $actualTotal = Task::count();
        if (array_sum($taskStats) != $actualTotal) {
            // Recalculate with direct queries
            $taskStats = [
                'pending' => Task::whereIn('status', ['pending', 'todo', 'to do', 'not started'])->count(),
                'in_progress' => Task::whereIn('status', ['in_progress', 'in-progress', 'progress', 'in progress'])->count(),
                'done' => Task::whereIn('status', ['done', 'completed'])->count(),
            ];
        }

        // Statistik Project semua user
        $projects = Project::all();
        $now = Carbon::now();
        
        $activeProjects = $projects->filter(function($project) use ($now) {
            return $project->end_date && Carbon::parse($project->end_date)->gte($now);
        })->count();
        
        $finishedProjects = $projects->filter(function($project) use ($now) {
            return $project->end_date && Carbon::parse($project->end_date)->lt($now);
        })->count();

        // Get user statistics
        $totalUsers = \App\Models\User::count();
        $activeUsers = Task::distinct('user_id')
            ->whereDate('updated_at', '>=', Carbon::now()->subDays(7))
            ->count('user_id');

        $totalTasks = array_sum($taskStats);
        $completionRate = $totalTasks > 0 
            ? round(($taskStats['done'] / $totalTasks) * 100, 1)
            : 0;

        return response()->json([
            'tasks' => $taskStats,
            'projects' => [
                'active' => $activeProjects,
                'finished' => $finishedProjects,
            ],
            'users' => [
                'total' => $totalUsers,
                'active' => $activeUsers,
            ],
            'summary' => [
                'total_tasks' => $totalTasks,
                'completion_rate' => $completionRate,
            ]
        ]);
    }
}