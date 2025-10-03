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
                    WHEN status = 'done' THEN 'done'
                    WHEN status IN ('in_progress', 'in-progress', 'progress') THEN 'in_progress'
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

        // Statistik Project user
        $projects = Project::where('user_id', $userId)->get();
        $now = Carbon::now();
        
        $activeProjects = $projects->filter(function($project) use ($now) {
            return Carbon::parse($project->end_date)->gte($now);
        })->count();
        
        $finishedProjects = $projects->filter(function($project) use ($now) {
            return Carbon::parse($project->end_date)->lt($now);
        })->count();

        // Weekly trend data (last 7 days)
        $weeklyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Task::where('user_id', $userId)
                ->where('status', 'done')
                ->whereDate('updated_at', $date)
                ->count();
            
            $weeklyTrend[] = [
                'date' => $date->format('D'),
                'count' => $count
            ];
        }

        return response()->json([
            'tasks' => $taskStats,
            'projects' => [
                'active' => $activeProjects,
                'finished' => $finishedProjects,
            ],
            'weekly_trend' => $weeklyTrend,
            'summary' => [
                'total_tasks' => array_sum($taskStats),
                'completion_rate' => array_sum($taskStats) > 0 
                    ? round(($taskStats['done'] / array_sum($taskStats)) * 100, 1)
                    : 0,
            ]
        ]);
    }

    // === Analytics Global untuk Admin ===
    public function adminIndex()
    {
        return view('admin.analyticts.index'); // Fix typo: analyticts -> analytics
    }

    public function adminData()
    {
        // Statistik Task semua user dengan normalisasi status
        $tasks = Task::selectRaw("
                CASE 
                    WHEN status = 'done' THEN 'done'
                    WHEN status IN ('in_progress', 'in-progress', 'progress') THEN 'in_progress'
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

        // Statistik Project semua user
        $projects = Project::all();
        $now = Carbon::now();
        
        $activeProjects = $projects->filter(function($project) use ($now) {
            return Carbon::parse($project->end_date)->gte($now);
        })->count();
        
        $finishedProjects = $projects->filter(function($project) use ($now) {
            return Carbon::parse($project->end_date)->lt($now);
        })->count();

        // Get user statistics
        $totalUsers = \App\Models\User::count();
        $activeUsers = Task::distinct('user_id')
            ->whereDate('updated_at', '>=', Carbon::now()->subDays(7))
            ->count('user_id');

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
                'total_tasks' => array_sum($taskStats),
                'completion_rate' => array_sum($taskStats) > 0 
                    ? round(($taskStats['done'] / array_sum($taskStats)) * 100, 1)
                    : 0,
            ]
        ]);
    }
}