<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AdminNotification;
use App\Notifications\TaskAssignedNotification;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->with('project')
            ->latest()
            ->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    public function create(Request $request)
    {
        $projects = Project::where('user_id', Auth::id())->get();
        $selectedProjectId = $request->query('project_id');
        
        return view('tasks.create', compact('projects', 'selectedProjectId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
        ]);

        // Pastikan project milik user
        if ($validated['project_id']) {
            $project = Project::where('id', $validated['project_id'])
                ->where('user_id', auth()->id())
                ->firstOrFail();
        }

        $validated['user_id'] = auth()->id();
        $task = Task::create($validated);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AdminNotification(
                'New Task Created',
                auth()->user()->name . ' created a new task: ' . $task->title,
                route('tasks.show', $task)
            ));
        }

        if ($request->query('from') === 'project' && $task->project_id) {
            return redirect()->route('projects.show', $task->project_id)
                ->with('success', 'Task created successfully.');
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task->load('project');
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $projects = Project::where('user_id', Auth::id())->get();
        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
        ]);

        if ($validated['project_id']) {
            $project = Project::where('id', $validated['project_id'])
                ->where('user_id', auth()->id())
                ->firstOrFail();
        }

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}