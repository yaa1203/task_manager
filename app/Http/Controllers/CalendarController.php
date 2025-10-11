<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workspace;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        // Ambil semua tasks dari workspace yang di-assign ke user
        $allMyTasks = collect();
        $workspaces = Workspace::whereHas('tasks.assignedUsers', function ($q) {
            $q->where('user_id', Auth::id());
        })->with(['tasks' => function($q) {
            $q->whereHas('assignedUsers', function($query) {
                $query->where('user_id', Auth::id());
            })->with(['submissions' => function($query) {
                $query->where('user_id', Auth::id());
            }]);
        }])->get();

        foreach ($workspaces as $workspace) {
            $allMyTasks = $allMyTasks->merge($workspace->tasks);
        }

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

        $stats = [
            'total_tasks' => $totalTasks,
            'completed_tasks' => $doneTasks,
            'overdue_tasks' => $overdueTasks,
            'unfinished_tasks' => $unfinishedTasks,
        ];
        
        return view('calendar.index', compact('stats'));
    }

    /**
     * API endpoint untuk ambil data event calendar (tasks only)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function events(Request $request)
    {
        // Ambil semua tasks dari workspace yang di-assign ke user
        $allMyTasks = collect();
        $workspaces = Workspace::whereHas('tasks.assignedUsers', function ($q) {
            $q->where('user_id', Auth::id());
        })->with(['tasks' => function($q) {
            $q->whereHas('assignedUsers', function($query) {
                $query->where('user_id', Auth::id());
            })->with(['submissions' => function($query) {
                $query->where('user_id', Auth::id());
            }]);
        }])->get();

        foreach ($workspaces as $workspace) {
            foreach ($workspace->tasks as $task) {
                $task->workspace = $workspace; // Attach workspace info
                $allMyTasks->push($task);
            }
        }

        $tasks = $allMyTasks->map(function ($task) {
            $hasSubmission = $task->submissions->isNotEmpty();
            $isOverdue = false;
            
            if ($task->due_date && !$hasSubmission) {
                $isOverdue = Carbon::parse($task->due_date)->isPast();
            }
            
            $isDone = $hasSubmission;
            
            // Tentukan warna berdasarkan status
            $color = $isDone ? '#10b981' : ($isOverdue ? '#ef4444' : '#6b7280');
            $borderColor = $isDone ? '#059669' : ($isOverdue ? '#dc2626' : '#4b5563');
            
            return [
                'id' => 'task-' . $task->id,
                'title' => $task->title,
                'start' => $task->due_date,
                'end' => $task->due_date,
                'backgroundColor' => $color,
                'borderColor' => $borderColor,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'workspaceName' => $task->workspace->name ?? 'N/A',
                    'workspaceIcon' => $task->workspace->icon ?? '📁',
                    'priority' => $task->priority ?? 'medium',
                    'description' => $task->description ?? '',
                    'dueDate' => $task->due_date ? Carbon::parse($task->due_date)->format('d M Y') : null,
                    'isDone' => $isDone,
                    'isOverdue' => $isOverdue,
                ],
                'url' => route('my-workspaces.task.show', [$task->workspace_id, $task->id]),
            ];
        });

        return response()->json($tasks);
    }
}