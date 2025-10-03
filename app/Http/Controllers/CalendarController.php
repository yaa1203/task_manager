<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tasks' => Task::where('user_id', Auth::id())->count(),
            'completed_tasks' => Task::where('user_id', Auth::id())->where('status', 'done')->count(),
            'overdue_tasks' => Task::where('user_id', Auth::id())
                ->where('status', '!=', 'done')
                ->where('due_date', '<', Carbon::today())
                ->count(),
            'total_projects' => Project::where('user_id', Auth::id())->count(),
        ];
        
        return view('calendar.index', compact('stats'));
    }

    // API endpoint untuk ambil data event (task & project)
    public function events(Request $request)
    {
        $tasks = Task::where('user_id', Auth::id())->get()->map(function ($task) {
            $color = match($task->status) {
                'done' => '#10b981',
                'in_progress' => '#3b82f6',
                'pending' => '#f59e0b',
                default => '#6b7280'
            };
            
            $isOverdue = $task->status !== 'done' && Carbon::parse($task->due_date)->isPast();
            
            return [
                'id' => 'task-' . $task->id,
                'title' => $task->title,
                'start' => $task->due_date,
                'end' => $task->due_date,
                'backgroundColor' => $isOverdue ? '#ef4444' : $color,
                'borderColor' => $isOverdue ? '#dc2626' : $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'type' => 'task',
                    'status' => $task->status,
                    'priority' => $task->priority ?? 'medium',
                    'description' => $task->description ?? '',
                    'isOverdue' => $isOverdue
                ],
                'url' => route('tasks.show', $task),
            ];
        });

        $projects = Project::where('user_id', Auth::id())->get()->map(function ($project) {
            return [
                'id' => 'project-' . $project->id,
                'title' => '📁 ' . $project->name,
                'start' => $project->start_date,
                'end' => Carbon::parse($project->end_date)->addDay(), // FullCalendar exclusive end
                'backgroundColor' => '#8b5cf6',
                'borderColor' => '#7c3aed',
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'type' => 'project',
                    'description' => $project->description ?? '',
                ],
                'url' => route('projects.show', $project),
                'display' => 'block' // Tampil sebagai block untuk multi-day events
            ];
        });

        return response()->json($tasks->merge($projects));
    }

    // Quick create task dari calendar
    public function quickCreateTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $task = Task::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'due_date' => $request->due_date,
            'priority' => $request->priority ?? 'medium',
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'task' => $task,
            'message' => 'Task created successfully!'
        ]);
    }

    // Update task date via drag & drop
    public function updateTaskDate(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update([
            'due_date' => $request->due_date
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task date updated!'
        ]);
    }

    // Update project dates via drag & drop
    public function updateProjectDate(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $project->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project dates updated!'
        ]);
    }
}