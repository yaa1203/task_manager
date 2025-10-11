<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserTaskSubmission;
use App\Notifications\TaskAssignedNotification;

class WorkspaceController extends Controller
{
    /**
     * Display a listing of workspaces
     */
    public function index()
    {
        $workspaces = Workspace::where('user_id', Auth::id())
            ->active()
            ->withCount(['tasks'])
            ->latest()
            ->get();

        $archivedWorkspaces = Workspace::where('user_id', Auth::id())
            ->archived()
            ->withCount(['tasks'])
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
        ]);

        $validated['user_id'] = Auth::id();

        $workspace = Workspace::create($validated);

        return redirect()->route('workspaces.show', $workspace)
            ->with('success', 'Workspace created successfully!');
    }

    /**
     * Display the specified workspace
     */
    public function show(Workspace $workspace)
    {
        $this->authorize('view', $workspace);

        // Load tasks with their assigned users and submissions
        $workspace->load(['tasks.assignedUsers', 'tasks.submissions']);

        // Get all users for task assignment
        $users = User::where('role', 'user')->get();

        return view('admin.workspace.show', compact('workspace', 'users'));
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
     * Create task in workspace
     */
    public function createTask(Workspace $workspace)
    {
        $this->authorize('update', $workspace);
        
        $users = User::where('role', 'user')->get();

        return view('admin.workspace.tasks.create', compact('workspace', 'users'));
    }

    /**
     * Store task in workspace
     */
    public function storeTask(Request $request, Workspace $workspace)
    {
        $this->authorize('update', $workspace);

        $validated = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'assign_to_all' => 'nullable|boolean',
        ]);

        // Get user IDs
        if ($request->assign_to_all) {
            $userIds = User::where('role', 'user')->pluck('id')->toArray();
        } else {
            $userIds = $validated['user_ids'];
        }

        // Create single task
        $task = Task::create([
            'workspace_id' => $workspace->id,
            'created_by' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'],
        ]);

        // Attach users to task
        $task->assignedUsers()->attach($userIds);

        // Send notifications to all assigned users
        foreach ($userIds as $userId) {
            $assignedUser = User::find($userId);
            if ($assignedUser) {
                $assignedUser->notify(new TaskAssignedNotification(
                    $task,
                    auth()->user()->name
                ));
            }
        }

        $userCount = count($userIds);
        return redirect()->route('workspaces.show', $workspace)
            ->with('success', "Task created and assigned to {$userCount} user(s) successfully!");
    }

    /**
     * Edit task in workspace
     */
    public function editTask(Workspace $workspace, Task $task)
    {
        $this->authorize('update', $workspace);
        
        if ($task->workspace_id !== $workspace->id) {
            abort(404);
        }

        $users = User::where('role', 'user')->get();

        return view('admin.workspace.tasks.edit', compact('workspace', 'task', 'users'));
    }

    /**
     * Update task in workspace
     */
    public function updateTask(Request $request, Workspace $workspace, Task $task)
    {
        $this->authorize('update', $workspace);

        if ($task->workspace_id !== $workspace->id) {
            abort(404);
        }

        $validated = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
        ]);

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'],
        ]);

        // Sync assigned users
        $task->assignedUsers()->sync($validated['user_ids']);

        return redirect()->route('workspaces.show', $workspace)
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Display task details with submissions
     */
    public function showTask(Workspace $workspace, Task $task)
    {
        $this->authorize('view', $workspace);
        
        if ($task->workspace_id !== $workspace->id) {
            abort(404);
        }

        $task->load(['submissions.user', 'assignedUsers']);

        return view('admin.workspace.tasks.show', compact('workspace', 'task'));
    }

    /**
     * Delete task from workspace
     */
    public function destroyTask(Workspace $workspace, Task $task)
    {
        $this->authorize('update', $workspace);

        if ($task->workspace_id !== $workspace->id) {
            abort(404);
        }

        $task->delete();

        return redirect()->route('workspaces.show', $workspace)
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * User workspace index
     */
    public function userIndex()
    {
        $workspaces = Workspace::whereHas('tasks.assignedUsers', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->withCount(['tasks'])
            ->get();

        return view('work.index', compact('workspaces'));
    }

    /**
     * User workspace detail
     */
    public function userShow(Workspace $workspace)
    {
        $isAccessible = $workspace->tasks()->whereHas('assignedUsers', function ($q) {
                $q->where('user_id', Auth::id());
            })->exists();

        abort_unless($isAccessible, 403);

        $tasks = $workspace->tasks()
            ->whereHas('assignedUsers', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->with(['submissions' => fn($q) => $q->where('user_id', Auth::id())])
            ->get();

        return view('work.show', compact('workspace', 'tasks'));
    }

    /**
     * User view task detail
     */
    public function userShowTask(Workspace $workspace, Task $task)
    {
        $isAccessible = $task->assignedUsers()
            ->where('user_id', Auth::id())
            ->exists();

        abort_unless($isAccessible, 403);

        if ($task->workspace_id !== $workspace->id) {
            abort(404);
        }

        $submissions = $task->submissions()
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $hasSubmitted = $submissions->isNotEmpty();

        return view('work.tasks.show', compact('workspace', 'task', 'submissions', 'hasSubmitted'));
    }

    /**
     * User submit task
     */
    public function submitTask(Request $request, Workspace $workspace, Task $task)
    {
        // Check if user has access to this task
        $isAccessible = $task->assignedUsers()
            ->where('user_id', Auth::id())
            ->exists();

        abort_unless($isAccessible, 403);

        if ($task->workspace_id !== $workspace->id) {
            abort(404);
        }

        $request->validate([
            'file' => 'nullable|file|max:10240',
            'link' => 'nullable|url',
            'notes' => 'nullable|string|max:1000',
        ]);

        $filePath = $request->hasFile('file')
            ? $request->file('file')->store('submissions', 'public')
            : null;

        // Update or Create submission
        UserTaskSubmission::updateOrCreate(
            [
                'task_id' => $task->id,
                'user_id' => Auth::id(),
            ],
            [
                'file_path' => $filePath,
                'link' => $request->link,
                'notes' => $request->notes,
                'status' => 'pending',
                'submitted_at' => now(),
            ]
        );

        return back()->with('success', 'Submission sent successfully!');
    }
}