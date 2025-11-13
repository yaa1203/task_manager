<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    // Cache duration in seconds (2 minutes) - hanya untuk user analytics
    const CACHE_DURATION = 120;

    /**
     * Display analytics dashboard for user
     */
    public function index()
    {
        return view('analytics.index');
    }

    /**
     * Get analytics data for user with caching
     */
    public function data()
    {
        try {
            $userId = Auth::id();
            
            if (!$userId) {
                return $this->errorResponse('Unauthorized', 'User not authenticated', 401);
            }

            Log::info('Fetching analytics for user: ' . $userId);

            // Use cache to prevent excessive database queries
            $cacheKey = "analytics_user_{$userId}";
            
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($userId) {
                return $this->getUserAnalyticsData($userId);
            });

            return $this->successResponse($data);

        } catch (\Exception $e) {
            Log::error('Analytics data error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse('Internal Server Error', 'Failed to load analytics data', 500);
        }
    }

    /**
     * Get user analytics data
     * FIXED: Filter out archived workspaces
     */
    private function getUserAnalyticsData($userId)
    {
        // ✅ HANYA ambil workspace yang TIDAK diarsipkan (is_archived = false)
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
        }])->get();

        // Collect all tasks
        $allMyTasks = collect();
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

        // Calculate task statistics
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
        
        $unfinishedTasks = max(0, $totalTasks - $doneTasks - $overdueTasks);

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

        return [
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
    }

    /**
     * Success response helper
     */
    private function successResponse($data)
    {
        return response()->json($data)
            ->header('Content-Type', 'application/json; charset=utf-8')
            ->header('Cache-Control', 'private, max-age=' . self::CACHE_DURATION)
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('X-Frame-Options', 'DENY');
    }

    /**
     * Error response helper
     */
    private function errorResponse($error, $message, $statusCode = 500)
    {
        $defaultData = [
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
        ];

        return response()->json(array_merge([
            'error' => $error,
            'message' => $message,
        ], $defaultData), $statusCode)
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
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
     * ✅ FIXED: Get admin analytics data WITHOUT caching
     * Data selalu fresh dari database untuk menghindari stale data
     * ✅ UPDATED: Total users berdasarkan SEMUA user dengan kategori yang sama (tidak harus ditugaskan)
     */
    public function adminData()
    {
        try {
            $adminId = Auth::id();
            $admin = Auth::user();

            if (!$adminId || !$admin) {
                return $this->adminErrorResponse('Unauthorized', 'Admin not authenticated', 401);
            }

            Log::info('Fetching FRESH admin analytics for admin: ' . $adminId);

            // ✅ TIDAK PAKAI CACHE - langsung ambil data fresh
            $data = $this->getAdminAnalyticsData($adminId, $admin->category_id);

            // Return dengan no-cache headers
            return response()->json($data)
                ->header('Content-Type', 'application/json; charset=utf-8')
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT')
                ->header('X-Content-Type-Options', 'nosniff')
                ->header('X-Frame-Options', 'DENY');

        } catch (\Exception $e) {
            Log::error('Admin analytics data error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return $this->adminErrorResponse('Internal Server Error', 'Failed to load admin analytics data', 500);
        }
    }

    /**
     * ✅ UPDATED: Get admin analytics data
     * FIXED: Filter out archived workspaces
     * UPDATED: Total users dan active users berdasarkan kategori yang sama (tidak harus ditugaskan)
     */
    private function getAdminAnalyticsData($adminId, $categoryId)
    {
        // ✅ HANYA ambil workspace yang TIDAK diarsipkan (is_archived = false)
        $workspaces = Workspace::where('admin_id', $adminId)
            ->where('is_archived', false)
            ->with(['tasks.assignedUsers', 'tasks.submissions'])
            ->get();

        // Collect all tasks owned by admin (from non-archived workspaces only)
        $allTasks = $workspaces->flatMap->tasks;

        $totalAssignments = 0;
        $completedAssignments = 0;
        $overdueAssignments = 0;
        $unfinishedAssignments = 0;

        foreach ($allTasks as $task) {
            $assignedUserCount = $task->assignedUsers->count();
            $totalAssignments += $assignedUserCount;

            foreach ($task->assignedUsers as $user) {
                $userSubmission = $task->submissions->where('user_id', $user->id)->first();
                
                if ($userSubmission) {
                    $completedAssignments++;
                } else {
                    if ($task->due_date && Carbon::parse($task->due_date)->isPast()) {
                        $overdueAssignments++;
                    } else {
                        $unfinishedAssignments++;
                    }
                }
            }
        }

        $taskStats = [
            'overdue' => $overdueAssignments,
            'unfinished' => $unfinishedAssignments,
            'done' => $completedAssignments,
        ];

        // ✅ UPDATED: Hitung SEMUA user berdasarkan kategori yang sama (tidak harus ditugaskan)
        $totalUsers = User::where('role', 'user')
            ->where('category_id', $categoryId)
            ->count();

        // ✅ UPDATED: Active users (submitted dalam 7 hari terakhir) - dari kategori yang sama
        $activeUserIds = collect();
        foreach ($allTasks as $task) {
            if (!$task->submissions) continue;

            $recentSubmissions = $task->submissions->filter(function($submission) {
                return $submission->submitted_at && Carbon::parse($submission->submitted_at)->gte(Carbon::now()->subDays(7));
            });

            foreach ($recentSubmissions as $submission) {
                $activeUserIds->push($submission->user_id);
            }
        }

        // Count active users dari kategori yang sama
        $activeUsers = User::where('role', 'user')
            ->where('category_id', $categoryId)
            ->whereIn('id', $activeUserIds->unique())
            ->count();

        $completionRate = $totalAssignments > 0
            ? round(($completedAssignments / $totalAssignments) * 100, 1)
            : 0;

        return [
            'tasks' => $taskStats,
            'users' => [
                'total' => $totalUsers,
                'active' => $activeUsers,
            ],
            'summary' => [
                'total_tasks' => $totalAssignments,
                'completion_rate' => $completionRate,
                'unfinished_workload' => $unfinishedAssignments,
                'overdue_workload' => $overdueAssignments,
            ],
            'timestamp' => time(),
        ];
    }

    /**
     * Admin error response helper (no cache)
     */
    private function adminErrorResponse($error, $message, $statusCode = 500)
    {
        $defaultData = [
            'tasks' => [
                'done' => 0,
                'unfinished' => 0,
                'overdue' => 0
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
        ];

        return response()->json(array_merge([
            'error' => $error,
            'message' => $message,
        ], $defaultData), $statusCode)
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    }

    /**
     * ✅ HELPER: Clear analytics cache for specific user
     */
    public static function clearUserCache($userId)
    {
        Cache::forget("analytics_user_{$userId}");
        Log::info("Cleared analytics cache for user: {$userId}");
    }

    /**
     * ✅ HELPER: Clear analytics cache for specific admin
     */
    public static function clearAdminCache($adminId)
    {
        Cache::forget("analytics_admin_{$adminId}");
        Log::info("Cleared analytics cache for admin: {$adminId}");
    }

    /**
     * ✅ HELPER: Clear all related caches when workspace is archived/restored
     */
    public static function clearWorkspaceRelatedCaches(Workspace $workspace)
    {
        self::clearAdminCache($workspace->admin_id);

        $userIds = $workspace->tasks()
            ->with('assignedUsers')
            ->get()
            ->pluck('assignedUsers')
            ->flatten()
            ->pluck('id')
            ->unique();

        foreach ($userIds as $userId) {
            self::clearUserCache($userId);
        }

        Log::info("Cleared all analytics caches for workspace: {$workspace->id}");
    }
    

    public function SuperadminIndex()
    {
        return view('superadmin.analytic.index');
    }

    public function SuperadminData()
    {
        try {
            $superAdminId = Auth::id();

            if (!$superAdminId) {
                return $this->adminErrorResponse('Unauthorized', 'Superadmin not authenticated', 401);
            }

            Log::info('Fetching analytics (admins + users only) for Superadmin: ' . $superAdminId);

            // Ambil semua workspace aktif
            $workspaces = Workspace::where('is_archived', false)
                ->get(['id', 'admin_id', 'name', 'icon', 'color']);

            if ($workspaces->isEmpty()) {
                return response()->json([
                    'tasks' => ['done' => 0, 'unfinished' => 0, 'overdue' => 0],
                    'accounts' => ['total' => 0, 'active' => 0, 'new_this_week' => 0],
                    'admins' => ['total' => 0],
                    'workspaces' => ['total' => 0, 'breakdown' => []],
                    'summary' => [
                        'total_tasks' => 0,
                        'completion_rate' => 0,
                        'unfinished_workload' => 0,
                        'overdue_workload' => 0
                    ],
                    'timestamp' => time(),
                ]);
            }

            // Ambil semua task dari tiap workspace
            $allTasks = collect();
            foreach ($workspaces as $ws) {
                $tasks = $ws->tasks()
                    ->with(['assignedUsers:id', 'submissions:id,task_id,user_id'])
                    ->get(['id', 'workspace_id', 'due_date']);
                $allTasks = $allTasks->merge($tasks);
            }

            // Hitung status tugas
            $completed = $unfinished = $overdue = 0;
            $totalAssignments = 0;

            foreach ($allTasks as $task) {
                $assignedUsers = $task->assignedUsers ?? collect();
                $submissions = $task->submissions ?? collect();
                $totalAssignments += $assignedUsers->count();

                foreach ($assignedUsers as $user) {
                    $hasSubmitted = $submissions->first(fn($s) => $s->user_id == $user->id);
                    if ($hasSubmitted) {
                        $completed++;
                    } else {
                        $isOverdue = !empty($task->due_date) && \Carbon\Carbon::parse($task->due_date)->isPast();
                        $isOverdue ? $overdue++ : $unfinished++;
                    }
                }
            }

            // Ambil semua user & admin saja
            $allAccounts = User::whereIn('role', ['admin', 'user'])
                ->get(['id', 'name', 'role', 'created_at']);

            $totalUsers = $allAccounts->where('role', 'user')->count();
            $totalAdmins = $allAccounts->where('role', 'admin')->count();

            // User aktif (yang kirim submission 7 hari terakhir)
            $recentUserIds = DB::table('user_task_submissions')
                ->whereNotNull('created_at')
                ->where('created_at', '>=', now()->subDays(7))
                ->distinct()
                ->pluck('user_id')
                ->toArray();

            $activeAccounts = $allAccounts->whereIn('id', $recentUserIds)->count();

            // Statistik waktu
            $newThisWeek = $allAccounts->where('created_at', '>=', now()->subDays(7))->count();

            // Hitung rate penyelesaian
            $completionRate = $totalAssignments > 0
                ? round(($completed / $totalAssignments) * 100, 1)
                : 0;

            // Breakdown workspace per admin
            $adminIds = $workspaces->pluck('admin_id')->unique()->filter()->values()->all();
            $admins = User::whereIn('id', $adminIds)->get(['id', 'name'])->keyBy('id');

            $workspaceBreakdown = $workspaces->groupBy('admin_id')->map(function ($group, $adminId) use ($admins) {
                $adminName = $admins[$adminId]->name ?? 'Unknown Admin';
                return [
                    'admin_id' => $adminId,
                    'admin_name' => $adminName,
                    'workspaces' => $group->count(),
                ];
            })->values()->all();

            // Ringkasan
            $summary = [
                'total_tasks' => $totalAssignments,
                'completion_rate' => $completionRate,
                'unfinished_workload' => $unfinished,
                'overdue_workload' => $overdue,
                'total_admins' => $totalAdmins,
                'total_users' => $totalUsers,
            ];

            // Response final
            return response()->json([
                'tasks' => [
                    'done' => $completed,
                    'unfinished' => $unfinished,
                    'overdue' => $overdue,
                ],
                'accounts' => [
                    'total' => $allAccounts->count(),
                    'active' => $activeAccounts,
                    'new_this_week' => $newThisWeek,
                ],
                'admins' => ['total' => $totalAdmins],
                'workspaces' => [
                    'total' => $workspaces->count(),
                    'breakdown' => $workspaceBreakdown,
                ],
                'summary' => $summary,
                'timestamp' => time(),
            ]);

        } catch (\Exception $e) {
            Log::error('Superadmin analytics error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}