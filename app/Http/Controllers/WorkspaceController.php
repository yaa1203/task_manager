<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    /**
     * Display a listing of workspaces
     */
    public function index()
    {
        $workspaces = Workspace::where('user_id', Auth::id())
            ->active()
            ->withCount(['tasks', 'projects'])
            ->latest()
            ->get();

        $archivedWorkspaces = Workspace::where('user_id', Auth::id())
            ->archived()
            ->withCount(['tasks', 'projects'])
            ->latest()
            ->get();

        return view('admin.workspace.index', compact('workspaces', 'archivedWorkspaces'));
    }

    /**
     * Show the form for creating a new workspace
     */
    public function create()
    {
        $colors = [
            '#6366f1' => 'Indigo',
            '#8b5cf6' => 'Purple',
            '#ec4899' => 'Pink',
            '#f43f5e' => 'Rose',
            '#ef4444' => 'Red',
            '#f97316' => 'Orange',
            '#f59e0b' => 'Amber',
            '#eab308' => 'Yellow',
            '#84cc16' => 'Lime',
            '#22c55e' => 'Green',
            '#10b981' => 'Emerald',
            '#14b8a6' => 'Teal',
            '#06b6d4' => 'Cyan',
            '#0ea5e9' => 'Sky',
            '#3b82f6' => 'Blue',
        ];

        $icons = [
            '📁', '📂', '📊', '💼', '🎯', '⚡', '🚀', '💡',
            '🎨', '📚', '🔧', '⚙️', '🎓', '💻', '📱', '🌟',
            '🔥', '✨', '🎪', '🎭', '🎬', '🎮', '🎲', '🎯'
        ];

        return view('admin.workspace.create', compact('colors', 'icons'));
    }

    /**
     * Store a newly created workspace
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string',
            'icon' => 'required|string',
            'type' => 'required|in:project,task,mixed',
        ]);

        $validated['user_id'] = Auth::id();

        $workspace = Workspace::create($validated);

        return redirect()->route('workspace.show', $workspace)
            ->with('success', 'Workspace created successfully!');
    }

    /**
     * Display the specified workspace
     */
    public function show(Workspace $workspace)
    {
        $this->authorize('view', $workspace);

        $workspace->load(['tasks.project', 'projects']);

        $availableTasks = Task::where('user_id', Auth::id())
            ->whereDoesntHave('workspaces', function ($query) use ($workspace) {
                $query->where('workspace_id', $workspace->id);
            })
            ->get();

        $availableProjects = Project::where('user_id', Auth::id())
            ->whereDoesntHave('workspaces', function ($query) use ($workspace) {
                $query->where('workspace_id', $workspace->id);
            })
            ->get();

        return view('admin.workspace.show', compact('workspace', 'availableTasks', 'availableProjects'));
    }

    /**
     * Show the form for editing workspace
     */
    public function edit(Workspace $workspace)
    {
        $this->authorize('update', $workspace);

        $colors = [
            '#6366f1' => 'Indigo',
            '#8b5cf6' => 'Purple',
            '#ec4899' => 'Pink',
            '#f43f5e' => 'Rose',
            '#ef4444' => 'Red',
            '#f97316' => 'Orange',
            '#f59e0b' => 'Amber',
            '#eab308' => 'Yellow',
            '#84cc16' => 'Lime',
            '#22c55e' => 'Green',
            '#10b981' => 'Emerald',
            '#14b8a6' => 'Teal',
            '#06b6d4' => 'Cyan',
            '#0ea5e9' => 'Sky',
            '#3b82f6' => 'Blue',
        ];

        $icons = [
            '📁', '📂', '📊', '💼', '🎯', '⚡', '🚀', '💡',
            '🎨', '📚', '🔧', '⚙️', '🎓', '💻', '📱', '🌟',
            '🔥', '✨', '🎪', '🎭', '🎬', '🎮', '🎲', '🎯'
        ];

        return view('admin.workspace.edit', compact('workspace', 'colors', 'icons'));
    }

    /**
     * Update the specified workspace
     */
    public function update(Request $request, Workspace $workspace)
    {
        $this->authorize('update', $workspace);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string',
            'icon' => 'required|string',
            'type' => 'required|in:project,task,mixed',
        ]);

        $workspace->update($validated);

        return redirect()->route('workspaces.show', $workspace)
            ->with('success', 'Workspace updated successfully!');
    }

    /**
     * Archive/Unarchive workspace
     */
    public function toggleArchive(Workspace $workspace)
    {
        $this->authorize('update', $workspace);

        $workspace->update([
            'is_archived' => !$workspace->is_archived
        ]);

        $message = $workspace->is_archived 
            ? 'Workspace archived successfully!' 
            : 'Workspace restored successfully!';

        return redirect()->route('workspaces.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified workspace
     */
    public function destroy(Workspace $workspace)
    {
        $this->authorize('delete', $workspace);

        $workspace->delete();

        return redirect()->route('workspaces.index')
            ->with('success', 'Workspace deleted successfully!');
    }

    /**
     * Add task to workspace
     */
    public function addTask(Request $request, Workspace $workspace)
    {
        $this->authorize('update', $workspace);

        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id'
        ]);

        $task = Task::findOrFail($validated['task_id']);

        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $workspace->tasks()->attach($validated['task_id']);

        return redirect()->route('workspaces.show', $workspace)
            ->with('success', 'Task added to workspace!');
    }

    /**
     * Remove task from workspace
     */
    public function removeTask(Workspace $workspace, Task $task)
    {
        $this->authorize('update', $workspace);

        $workspace->tasks()->detach($task->id);

        return redirect()->route('workspaces.show', $workspace)
            ->with('success', 'Task removed from workspace!');
    }

    /**
     * Add project to workspace
     */
    public function addProject(Request $request, Workspace $workspace)
    {
        $this->authorize('update', $workspace);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id'
        ]);

        $project = Project::findOrFail($validated['project_id']);

        if ($project->user_id !== Auth::id()) {
            abort(403);
        }

        $workspace->projects()->attach($validated['project_id']);

        return redirect()->route('workspaces.show', $workspace)
            ->with('success', 'Project added to workspace!');
    }

    /**
     * Remove project from workspace
     */
    public function removeProject(Workspace $workspace, Project $project)
    {
        $this->authorize('update', $workspace);

        $workspace->projects()->detach($project->id);

        return redirect()->route('workspaces.show', $workspace)
            ->with('success', 'Project removed from workspace!');
    }
}
