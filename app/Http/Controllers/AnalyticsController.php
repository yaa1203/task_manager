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
     */
    public function adminData()
    {
        try {
            $adminId = Auth::id();

            if (!$adminId) {
                return $this->adminErrorResponse('Unauthorized', 'Admin not authenticated', 401);
            }

            Log::info('Fetching FRESH admin analytics for admin: ' . $adminId);

            // ✅ TIDAK PAKAI CACHE - langsung ambil data fresh
            $data = $this->getAdminAnalyticsData($adminId);

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
     * Get admin analytics data
     * FIXED: Filter out archived workspaces
     */
    private function getAdminAnalyticsData($adminId)
    {
        // ✅ HANYA ambil workspace yang TIDAK diarsipkan (is_archived = false)
        $workspaces = Workspace::where('admin_id', $adminId)
            ->where('is_archived', false) // ⚠️ CRITICAL: Filter workspace yang diarsipkan
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

        // ✅ Hanya hitung user dari workspace yang TIDAK diarsipkan
        $totalUsers = User::where('role', 'user')
            ->whereHas('assignedTasks.workspace', function ($q) use ($adminId) {
                $q->where('admin_id', $adminId)
                  ->where('is_archived', false); // Filter workspace yang diarsipkan
            })
            ->count();

        // Active users (in last 7 days) - only from non-archived workspaces
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

        $activeUsers = User::where('role', 'user')
            ->whereHas('assignedTasks.workspace', function ($q) use ($adminId) {
                $q->where('admin_id', $adminId)
                  ->where('is_archived', false); // Filter workspace yang diarsipkan
            })
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
     * Dipanggil ketika workspace di-archive/restore
     */
    public static function clearUserCache($userId)
    {
        Cache::forget("analytics_user_{$userId}");
        Log::info("Cleared analytics cache for user: {$userId}");
    }

    /**
     * ✅ HELPER: Clear analytics cache for specific admin
     * Dipanggil ketika workspace di-archive/restore
     * NOTE: Admin data sekarang tidak di-cache, tapi tetap keep function ini untuk backward compatibility
     */
    public static function clearAdminCache($adminId)
    {
        Cache::forget("analytics_admin_{$adminId}");
        Log::info("Cleared analytics cache for admin: {$adminId}");
    }

    /**
     * ✅ HELPER: Clear all related caches when workspace is archived/restored
     * Dipanggil dari WorkspaceController
     */
    public static function clearWorkspaceRelatedCaches(Workspace $workspace)
    {
        // Clear admin cache (meskipun admin sudah no-cache, tetap clear untuk safety)
        self::clearAdminCache($workspace->admin_id);

        // Clear cache for all users assigned to tasks in this workspace
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
}