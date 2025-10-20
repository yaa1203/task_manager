<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        try {
            $userId = Auth::id();
            
            if (!$userId) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'User not authenticated'
                ], 401)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
            }

            Log::info('Fetching analytics for user: ' . $userId);

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
                        'name' => $workspace->name ?? 'Unnamed Workspace',
                        'tasks' => $taskCount,
                        'icon' => $workspace->icon ?? '📁',
                        'color' => $workspace->color ?? '#6b7280'
                    ];
                }
                $allMyTasks = $allMyTasks->merge($workspace->tasks);
            }

            // Hitung statistik tasks berdasarkan submissions
            $totalTasks = $allMyTasks->count();
            
            $doneTasks = $allMyTasks->filter(function($task) {
                return $task->submissions && $task->submissions->isNotEmpty();
            })->count();
            
            $overdueTasks = $allMyTasks->filter(function($task) {
                $hasSubmission = $task->submissions && $task->submissions->isNotEmpty();
                if (!$hasSubmission && $task->due_date) {
                    try {
                        return Carbon::parse($task->due_date)->isPast();
                    } catch (\Exception $e) {
                        Log::warning('Invalid due_date format for task: ' . $task->id);
                        return false;
                    }
                }
                return false;
            })->count();
            
            $unfinishedTasks = $totalTasks - $doneTasks - $overdueTasks;
            
            // Ensure no negative values
            $unfinishedTasks = max(0, $unfinishedTasks);

            $taskStats = [
                'done' => (int) $doneTasks,
                'unfinished' => (int) $unfinishedTasks,
                'overdue' => (int) $overdueTasks,
            ];

            // Weekly trend data (last 7 days)
            $weeklyTrend = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                
                $count = $allMyTasks->filter(function($task) use ($date) {
                    if (!$task->submissions || $task->submissions->isEmpty()) {
                        return false;
                    }
                    
                    return $task->submissions->filter(function($submission) use ($date) {
                        if (!$submission->submitted_at) {
                            return false;
                        }
                        try {
                            return Carbon::parse($submission->submitted_at)->isSameDay($date);
                        } catch (\Exception $e) {
                            return false;
                        }
                    })->isNotEmpty();
                })->count();
                
                $weeklyTrend[] = [
                    'date' => $date->format('D'),
                    'count' => (int) $count
                ];
            }

            // Calculate completion rate
            $completionRate = $totalTasks > 0 
                ? round(($doneTasks / $totalTasks) * 100, 1)
                : 0;

            $response = [
                'tasks' => $taskStats,
                'workspaces' => [
                    'total' => (int) $workspaces->count(),
                    'breakdown' => $workspaceBreakdown,
                ],
                'weekly_trend' => $weeklyTrend,
                'summary' => [
                    'total_tasks' => (int) $totalTasks,
                    'completion_rate' => (float) $completionRate,
                ],
                'timestamp' => time(),
            ];

            Log::info('Analytics data prepared successfully', [
                'user_id' => $userId,
                'total_tasks' => $totalTasks,
                'workspaces' => $workspaces->count()
            ]);

            return response()->json($response)
                ->header('Content-Type', 'application/json; charset=utf-8')
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT')
                ->header('X-Content-Type-Options', 'nosniff')
                ->header('X-Frame-Options', 'DENY');

        } catch (\Exception $e) {
            Log::error('Analytics data error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Failed to load analytics data',
                'tasks' => [
                    'done' => 0,
                    'unfinished' => 0,
                    'overdue' => 0
                ],
                'workspaces' => [
                    'total' => 0,
                    'breakdown' => []
                ],
                'weekly_trend' => [],
                'summary' => [
                    'total_tasks' => 0,
                    'completion_rate' => 0
                ]
            ], 500)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }
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
     * Get analytics data for admin (hanya user yang sudah diberi tugas)
     * Menghitung berdasarkan TOTAL ASSIGNMENTS, bukan total tasks
     */
    public function adminData()
    {
        try {
            Log::info('Fetching admin analytics');

            // Ambil semua tasks dengan submissions dan assigned users
            $allTasks = Task::with(['submissions', 'assignedUsers'])->get();
            
            // LOGIKA BARU: Hitung berdasarkan TOTAL ASSIGNMENTS
            // Setiap task * jumlah assigned users = total assignments
            $totalAssignments = 0;
            $completedAssignments = 0;
            $overdueAssignments = 0;
            $unfinishedAssignments = 0;

            foreach ($allTasks as $task) {
                $assignedUserCount = $task->assignedUsers->count();
                $totalAssignments += $assignedUserCount;

                // Untuk setiap user yang di-assign, cek statusnya
                foreach ($task->assignedUsers as $user) {
                    // Cek apakah user ini sudah submit
                    $userSubmission = $task->submissions->where('user_id', $user->id)->first();
                    
                    if ($userSubmission) {
                        // User sudah submit = completed
                        $completedAssignments++;
                    } else {
                        // User belum submit, cek apakah overdue atau unfinished
                        if ($task->due_date && Carbon::parse($task->due_date)->isPast()) {
                            // Overdue
                            $overdueAssignments++;
                        } else {
                            // Unfinished (belum deadline)
                            $unfinishedAssignments++;
                        }
                    }
                }
            }

            $taskStats = [
                'overdue' => (int) $overdueAssignments,
                'unfinished' => (int) $unfinishedAssignments,
                'done' => (int) $completedAssignments,
            ];

            // User statistics - HANYA user yang sudah pernah diberi tugas
            $totalUsers = User::where('role', 'user')
                ->whereHas('assignedTasks')
                ->count();
            
            // Active users = users yang punya submission dalam 7 hari terakhir
            // DAN sudah pernah diberi tugas
            $activeUserIds = collect();
            foreach ($allTasks as $task) {
                if (!$task->submissions) continue;
                
                $recentSubmissions = $task->submissions->filter(function($submission) {
                    if (!$submission->submitted_at) return false;
                    
                    try {
                        return Carbon::parse($submission->submitted_at)->gte(Carbon::now()->subDays(7));
                    } catch (\Exception $e) {
                        return false;
                    }
                });
                
                foreach ($recentSubmissions as $submission) {
                    $activeUserIds->push($submission->user_id);
                }
            }
            
            // Pastikan active users juga termasuk dalam user yang pernah diberi tugas
            $activeUsers = User::where('role', 'user')
                ->whereHas('assignedTasks')
                ->whereIn('id', $activeUserIds->unique())
                ->count();

            // Calculate completion rate berdasarkan ASSIGNMENTS
            $completionRate = $totalAssignments > 0 
                ? round(($completedAssignments / $totalAssignments) * 100, 1)
                : 0;

            $response = [
                'tasks' => $taskStats,
                'users' => [
                    'total' => (int) $totalUsers,
                    'active' => (int) $activeUsers,
                ],
                'summary' => [
                    'total_tasks' => (int) $totalAssignments, // Total assignments, bukan total tasks
                    'completion_rate' => (float) $completionRate,
                    'unfinished_workload' => (int) $unfinishedAssignments,
                    'overdue_workload' => (int) $overdueAssignments,
                ],
                'timestamp' => time(),
            ];

            Log::info('Admin analytics data prepared successfully', [
                'total_assignments' => $totalAssignments,
                'completed_assignments' => $completedAssignments,
                'total_users' => $totalUsers,
                'active_users' => $activeUsers,
                'completion_rate' => $completionRate
            ]);

            return response()->json($response)
                ->header('Content-Type', 'application/json; charset=utf-8')
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT')
                ->header('X-Content-Type-Options', 'nosniff')
                ->header('X-Frame-Options', 'DENY');

        } catch (\Exception $e) {
            Log::error('Admin analytics data error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Failed to load admin analytics data',
                'tasks' => [
                    'overdue' => 0,
                    'unfinished' => 0,
                    'done' => 0
                ],
                'users' => [
                    'total' => 0,
                    'active' => 0
                ],
                'summary' => [
                    'total_tasks' => 0,
                    'completion_rate' => 0,
                    'unfinished_workload' => 0,
                    'overdue_workload' => 0
                ]
            ], 500)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }
    }
}