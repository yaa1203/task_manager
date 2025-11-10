<?php
namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserTaskSubmission;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskSubmittedNotification;

class WorkspaceController extends Controller
{
    /**
     * Display a listing of workspaces
     */
    public function index()
    {
        $workspaces = Workspace::where('admin_id', Auth::id())
            ->active()
            ->withCount(['tasks'])
            ->latest()
            ->get();

        $archivedWorkspaces = Workspace::where('admin_id', Auth::id())
            ->archived()
            ->withCount(['tasks'])
            ->latest()
            ->get();

            
         $user = auth()->user();
    $category = $user->category ? $user->category->name : 'Belum memilih kategori';


        return view('admin.workspace.index', compact('workspaces', 'archivedWorkspaces', 'category', 'user'));
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
        $validated['admin_id'] = Auth::id();

        $workspace = Workspace::create($validated);

        // ✅ Clear admin analytics cache after creating workspace
        AnalyticsController::clearAdminCache(Auth::id());

        return redirect()->route('workspaces.show', $workspace)
            ->with('success', 'Workspace created successfully!');
    }

    /**
     * Display the specified workspace
     */
    public function show(Workspace $workspace)
    {
        $this->authorize('view', $workspace);

        if ($workspace->admin_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke workspace ini.');
        }

        // Load tasks with their assigned users and submissions
        $workspace->load(['tasks.assignedUsers', 'tasks.submissions']);

        // Get all users for task assignment (exclude blocked users)
        $users = User::where('role', 'user')
            ->where('is_blocked', false)
            ->get();

        return view('admin.workspace.show', compact('workspace', 'users'));
    }

    /**
     * Show the form for editing workspace
     */
    public function edit(Workspace $workspace)
    {
        $this->authorize('update', $workspace);

        if ($workspace->admin_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke workspace ini.');
        }

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

        if ($workspace->admin_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke workspace ini.');
        }

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
     * ✅ FIXED: Clear analytics cache when toggling archive status
     */
    public function toggleArchive(Workspace $workspace)
    {
        $this->authorize('update', $workspace);

        $workspace->update([
            'is_archived' => !$workspace->is_archived
        ]);

        // ✅ CRITICAL: Clear all related analytics caches
        AnalyticsController::clearWorkspaceRelatedCaches($workspace);

        $message = $workspace->is_archived 
            ? 'Workspace archived successfully!' 
            : 'Workspace restored successfully!';

        return redirect()->route('workspaces.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified workspace
     * ✅ FIXED: Clear analytics cache when deleting workspace
     */
    public function destroy(Workspace $workspace)
    {
        $this->authorize('delete', $workspace);

        if ($workspace->admin_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke workspace ini.');
        }

        // ✅ Clear all related analytics caches before deletion
        AnalyticsController::clearWorkspaceRelatedCaches($workspace);

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
        
        $admin = Auth::user();
        $adminCategoryId = $admin->category_id;
        $categoryName = $admin->category->name ?? 'Tidak ada kategori';
        
        $users = User::where('role', 'user')
            ->where('category_id', $adminCategoryId)
            ->where('is_blocked', false)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.workspace.tasks.create', compact('workspace', 'users', 'categoryName'));
    }

    /**
     * Store task in workspace
     * ✅ FIXED: Clear analytics cache when creating task
     */
    public function storeTask(Request $request, Workspace $workspace, Task $task)
    {
        $this->authorize('update', $workspace);

        $admin = Auth::user();
        $adminCategoryId = $admin->category_id;

        $validated = $request->validate([
            'assign_to_all' => 'nullable|boolean',
            'user_ids' => 'required_without:assign_to_all|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date_format:Y-m-d H:i:s',
            'file' => 'nullable|file|max:10240',
            'link' => 'nullable|url',
        ]);

        if ($request->assign_to_all) {
            $userIds = User::where('role', 'user')
                ->where('category_id', $adminCategoryId)
                ->where('is_blocked', false)
                ->pluck('id')
                ->toArray();
        } else {
            $validUserIds = User::where('role', 'user')
                ->where('category_id', $adminCategoryId)
                ->where('is_blocked', false)
                ->whereIn('id', $validated['user_ids'])
                ->pluck('id')
                ->toArray();
            
            if (count($validUserIds) !== count($validated['user_ids'])) {
                return back()->withErrors(['user_ids' => 'Beberapa user yang dipilih tidak termasuk dalam kategori Anda atau telah diblokir.']);
            }
            
            $userIds = $validUserIds;
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            if ($file->isValid()) {
                $filePath = $file->store('task_files', 'public');
                $originalFilename = $file->getClientOriginalName();
            }
        }

        $task = Task::create([
            'admin_id' => Auth::id(),
            'workspace_id' => $workspace->id,
            'created_by' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
            'original_filename' => $originalFilename ?? null,
            'link' => $validated['link'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
        ]);

        $task->assignedUsers()->attach($userIds);

        // ✅ Clear analytics cache for admin and all assigned users
        AnalyticsController::clearAdminCache(Auth::id());
        foreach ($userIds as $userId) {
            AnalyticsController::clearUserCache($userId);
        }

        foreach ($userIds as $userId) {
            $assignedUser = User::find($userId);
            if ($assignedUser) {
                $assignedUser->notify(new TaskAssignedNotification($task));
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

        if ($task->admin_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke task ini.');
        }
        
        if ($task->workspace_id !== $workspace->id) {
            abort(404);
        }

        $admin = Auth::user();
        $adminCategoryId = $admin->category_id;
        $categoryName = $admin->category->name ?? 'Tidak ada kategori';
        
        $users = User::where('role', 'user')
            ->where('category_id', $adminCategoryId)
            ->where('is_blocked', false)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.workspace.tasks.edit', compact('workspace', 'task', 'users', 'categoryName'));
    }

    /**
     * Update task in workspace
     * ✅ FIXED: Clear analytics cache when updating task
     * ✅ FIXED: Send notification only to newly assigned users
     */
    public function updateTask(Request $request, Workspace $workspace, Task $task)
    {
        $this->authorize('update', $workspace);
        if ($task->admin_id !== Auth::id() || $task->workspace_id !== $workspace->id) {
            abort(403);
        }

        $admin = Auth::user();
        $adminCategoryId = $admin->category_id;

        $validated = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date_format:Y-m-d H:i:s',
            'file' => 'nullable|file|max:10240',
            'link' => 'nullable|url',
            'remove_file' => 'nullable|boolean',
        ]);

        $validUserIds = User::where('role', 'user')
            ->where('category_id', $adminCategoryId)
            ->where('is_blocked', false)
            ->whereIn('id', $validated['user_ids'])
            ->pluck('id')
            ->toArray();

        if (count($validUserIds) !== count($validated['user_ids'])) {
            return back()->withErrors(['user_ids' => 'Beberapa user tidak valid atau diblokir.']);
        }

        // ✅ Get old assigned users BEFORE sync
        $oldUserIds = $task->assignedUsers()->pluck('user_id')->toArray();

        $updateData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'link' => $validated['link'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
        ];

        if ($request->has('remove_file') && $request->remove_file) {
            if ($task->file_path) {
                Storage::disk('public')->delete($task->file_path);
            }
            $updateData['file_path'] = null;
            $updateData['original_filename'] = null;
        }
        elseif ($request->hasFile('file')) {
            if ($task->file_path) {
                Storage::disk('public')->delete($task->file_path);
            }
            $file = $request->file('file');
            $updateData['file_path'] = $file->store('task_files', 'public');
            $updateData['original_filename'] = $file->getClientOriginalName();
        }

        $task->update($updateData);
        $task->assignedUsers()->sync($validUserIds);

        // ✅ Find newly assigned users (users that are in new list but not in old list)
        $newlyAssignedUserIds = array_diff($validUserIds, $oldUserIds);

        // ✅ Send notification ONLY to newly assigned users
        if (!empty($newlyAssignedUserIds)) {
            foreach ($newlyAssignedUserIds as $userId) {
                $newUser = User::find($userId);
                if ($newUser) {
                    $newUser->notify(new TaskAssignedNotification($task));
                }
            }
        }

        // ✅ Clear analytics cache for admin and all affected users (old + new)
        AnalyticsController::clearAdminCache(Auth::id());
        $allAffectedUsers = array_unique(array_merge($oldUserIds, $validUserIds));
        foreach ($allAffectedUsers as $userId) {
            AnalyticsController::clearUserCache($userId);
        }

        $newUserCount = count($newlyAssignedUserIds);
        $successMessage = 'Task updated successfully!';
        
        if ($newUserCount > 0) {
            $successMessage .= " Notifikasi dikirim ke {$newUserCount} user baru.";
        }

        return redirect()->route('workspaces.show', $workspace)
            ->with('success', $successMessage);
    }

    /**
     * Display task details with submissions
     */
    public function showTask(Workspace $workspace, Task $task)
    {
        $this->authorize('view', $workspace);

        if ($task->admin_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke task ini.');
        }
        
        if ($task->workspace_id !== $workspace->id) {
            abort(404);
        }

        $task->load(['submissions.user', 'assignedUsers']);

        return view('admin.workspace.tasks.show', compact('workspace', 'task'));
    }

    /**
     * Delete task from workspace
     * ✅ FIXED: Clear analytics cache when deleting task
     */
    public function destroyTask(Workspace $workspace, Task $task)
    {
        $this->authorize('update', $workspace);

        if ($task->admin_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke task ini.');
        }

        if ($task->workspace_id !== $workspace->id) {
            abort(404);
        }

        // Get assigned users before deletion for cache clearing
        $userIds = $task->assignedUsers()->pluck('user_id')->toArray();

        $task->delete();

        // ✅ Clear analytics cache for admin and all assigned users
        AnalyticsController::clearAdminCache(Auth::id());
        foreach ($userIds as $userId) {
            AnalyticsController::clearUserCache($userId);
        }

        return redirect()->route('workspaces.show', $workspace)
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * User workspace index - FIXED: Only show non-archived workspaces
     */
    public function userIndex()
    {
        $workspaces = Workspace::whereHas('tasks.assignedUsers', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->where('is_archived', false)
            ->withCount(['tasks'])
            ->get();

        return view('work.index', compact('workspaces'));
    }

    /**
     * User workspace detail - FIXED: Check if workspace is archived
     */
    public function userShow(Workspace $workspace)
    {
        if ($workspace->is_archived) {
            abort(403, 'Workspace ini telah diarsipkan dan tidak dapat diakses.');
        }

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
     * User view task detail - FIXED: Check if workspace is archived
     */
    public function userShowTask(Workspace $workspace, Task $task)
    {
        if ($workspace->is_archived) {
            abort(403, 'Workspace ini telah diarsipkan dan tidak dapat diakses.');
        }

        $isAccessible = $task->assignedUsers()
            ->where('user_id', Auth::id())
            ->exists();

        abort_unless($isAccessible, 403);

        if ($task->workspace_id !== $workspace->id) {
            abort(404);
        }

        $task->load(['assignedUsers', 'creator']);
        
        $submissions = $task->submissions()
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $hasSubmitted = $submissions->isNotEmpty();

        return view('work.tasks.show', compact('workspace', 'task', 'submissions', 'hasSubmitted'));
    }

    /**
     * User submit task - FIXED: Check if workspace is archived
     * ✅ FIXED: Clear analytics cache when submitting task
     */
    public function submitTask(Request $request, Workspace $workspace, Task $task)
    {
        if ($workspace->is_archived) {
            abort(403, 'Workspace ini telah diarsipkan dan tidak dapat diakses.');
        }

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

        $submissionData = [
            'link' => $request->link,
            'notes' => $request->notes,
        ];
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            if ($file->isValid()) {
                $originalFilename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = uniqid() . '_' . time() . '.' . $extension;
                $filePath = $file->storeAs('submissions', $filename, 'public');
                
                $submissionData['file_path'] = $filePath;
                $submissionData['original_filename'] = $originalFilename;
                
                \Log::info('Submission Upload:', [
                    'original' => $originalFilename,
                    'stored' => $filePath,
                    'user' => Auth::id(),
                    'task' => $task->id
                ]);
            }
        }

        $submission = UserTaskSubmission::updateOrCreate(
            [
                'task_id' => $task->id,
                'user_id' => Auth::id(),
            ],
            $submissionData
        );

        \Log::info('Submission Result:', [
            'id' => $submission->id,
            'original_filename_saved' => $submission->original_filename,
        ]);

        // ✅ Clear analytics cache for user and admin
        AnalyticsController::clearUserCache(Auth::id());
        AnalyticsController::clearAdminCache($task->admin_id);

        $workspaceOwner = User::find($workspace->user_id);
        if ($workspaceOwner) {
            $workspaceOwner->notify(new TaskSubmittedNotification(
                $task,
                Auth::user(),
                $submission
            ));
        }

        return back()->with('success', 'Pengumpulan berhasil dikirim!');
    }

    /**
     * View task file in browser - FIXED: Check if workspace is archived
     */
    public function viewTaskFile(Workspace $workspace, Task $task)
    {
        if ($workspace->is_archived) {
            abort(403, 'Workspace ini telah diarsipkan dan tidak dapat diakses.');
        }

        if (Auth::user()->role === 'admin') {
            $this->authorize('view', $workspace);
        } else {
            $isAccessible = $task->assignedUsers()
                ->where('user_id', Auth::id())
                ->exists();
            
            if (!$isAccessible) {
                abort(403, 'You are not assigned to this task');
            }
        }
        
        if ($task->workspace_id !== $workspace->id) {
            abort(404);
        }

        if (!$task->file_path || !Storage::disk('public')->exists($task->file_path)) {
            abort(404, 'File not found');
        }

        $path = Storage::disk('public')->path($task->file_path);
        $mimeType = Storage::disk('public')->mimeType($task->file_path);
        $extension = strtolower(pathinfo($task->file_path, PATHINFO_EXTENSION));
        
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
        if (in_array($extension, $imageExtensions)) {
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($task->file_path) . '"'
            ]);
        }
        
        if ($extension === 'pdf') {
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($task->file_path) . '"'
            ]);
        }
        
        $textExtensions = ['txt', 'md', 'csv'];
        if (in_array($extension, $textExtensions)) {
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($task->file_path) . '"'
            ]);
        }
        
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($task->file_path) . '"',
            'X-Content-Type-Options' => 'nosniff'
        ]);
    }

    /**
     * Download task file - FIXED: Check if workspace is archived
     */
    public function downloadTaskFile(Workspace $workspace, Task $task)
    {
        if ($workspace->is_archived) {
            abort(403, 'Workspace ini telah diarsipkan dan tidak dapat diakses.');
        }

        if (Auth::user()->role === 'admin') {
            $this->authorize('view', $workspace);
        } else {
            $isAccessible = $task->assignedUsers()
                ->where('user_id', Auth::id())
                ->exists();
            
            if (!$isAccessible) {
                abort(403, 'You are not assigned to this task');
            }
        }
        
        if ($task->workspace_id !== $workspace->id) {
            abort(404);
        }

        if (!$task->file_path || !Storage::disk('public')->exists($task->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($task->file_path);
    }

    /**
     * View submission file in browser - FIXED: Check if workspace is archived
     */
    public function viewSubmissionFile(Workspace $workspace, Task $task, UserTaskSubmission $submission)
    {
        if ($workspace->is_archived) {
            abort(403, 'Workspace ini telah diarsipkan dan tidak dapat diakses.');
        }

        if (Auth::user()->role !== 'admin' && $submission->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$submission->file_path || !Storage::disk('public')->exists($submission->file_path)) {
            abort(404, 'File not found');
        }

        $path = Storage::disk('public')->path($submission->file_path);
        $mimeType = Storage::disk('public')->mimeType($submission->file_path);
        
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($submission->file_path) . '"'
        ]);
    }

    /**
     * Download submission file - FIXED: Check if workspace is archived
     */
    public function downloadSubmissionFile(Workspace $workspace, Task $task, UserTaskSubmission $submission)
    {
        if ($workspace->is_archived) {
            abort(403, 'Workspace ini telah diarsipkan dan tidak dapat diakses.');
        }

        if (Auth::user()->role !== 'admin' && $submission->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$submission->file_path || !Storage::disk('public')->exists($submission->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($submission->file_path);
    }

    /**
     * User calendar view - FIXED: Only show tasks from non-archived workspaces
     */
    public function userCalendar()
    {
        $tasks = Task::whereHas('assignedUsers', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->whereHas('workspace', function ($q) {
                $q->where('is_archived', false);
            })
            ->with([
                'workspace:id,name,color,icon',
                'submissions' => function($q) {
                    $q->where('user_id', Auth::id());
                }
            ])
            ->whereNotNull('due_date')
            ->orderBy('due_date', 'asc')
            ->get()
            ->map(function($task) {
                return [
                    'id' => $task->id,
                    'workspace_id' => $task->workspace_id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'priority' => $task->priority,
                    'due_date' => $task->due_date,
                    'workspace' => $task->workspace ? [
                        'name' => $task->workspace->name,
                        'color' => $task->workspace->color,
                        'icon' => $task->workspace->icon,
                    ] : null,
                    'submissions' => $task->submissions,
                ];
            });

        return view('calendar.index', compact('tasks'));
    }

    /// Di WorkspaceController.php, ubah method superadminIndex

    /**
     * Superadmin workspace index - Show all workspaces from all admins
     * ✅ FIXED: Default filter changed to 'all' instead of 'false'
     */
    public function superadminIndex(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $archived = $request->input('archived', 'all'); // ✅ Changed default from 'false' to 'all'
        
        $query = Workspace::withCount(['tasks'])
            ->with(['admin.category'])
            ->latest();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhereHas('admin', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }
        
        // ✅ Only filter when explicitly set to 'true' or 'false'
        if ($archived === 'true') {
            $query->where('is_archived', true);
        } elseif ($archived === 'false') {
            $query->where('is_archived', false);
        }
        // If $archived === 'all', no filter is applied (shows all)
        
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'updated_at':
                $query->orderBy('updated_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $workspaces = $query->paginate(15);
        
        $categories = \App\Models\Category::withCount([
            'users as admins_count' => function($query) {
                $query->where('role', 'admin');
            }
        ])->get();
        
        foreach ($categories as $category) {
            $category->workspaces_count = Workspace::whereHas('admin', function($q) use ($category) {
                $q->where('category_id', $category->id);
            })->count();
            
            $category->tasks_count = Task::whereHas('workspace.admin', function($q) use ($category) {
                $q->where('category_id', $category->id);
            })->count();
        }
        
        return view('superadmin.space.index', compact('workspaces', 'categories', 'search', 'sortBy', 'archived'));
    }

    /**
     * Superadmin workspace detail - Show workspace details from any admin
     */
    public function superadminShow(Workspace $workspace)
    {
        $workspace->load(['admin', 'tasks.assignedUsers', 'tasks.submissions']);
        
        $users = User::where('role', 'user')
            ->where('is_blocked', false)
            ->get();

        return view('superadmin.space.show', compact('workspace', 'users'));
    }

    /**
     * Superadmin toggle archive workspace
     * ✅ FIXED: Clear analytics cache when superadmin toggles archive
     */
    public function superadminToggleArchive(Workspace $workspace)
    {
        $workspace->update([
            'is_archived' => !$workspace->is_archived
        ]);

        // ✅ CRITICAL: Clear all related analytics caches
        AnalyticsController::clearWorkspaceRelatedCaches($workspace);

        $message = $workspace->is_archived 
            ? 'Workspace archived successfully!' 
            : 'Workspace restored successfully!';

        return redirect()->route('space.index')
            ->with('success', $message);
    }

    /**
     * Superadmin delete workspace
     * ✅ FIXED: Clear analytics cache when superadmin deletes workspace
     */
    public function superadminDestroy(Workspace $workspace)
    {
        // ✅ Clear all related analytics caches before deletion
        AnalyticsController::clearWorkspaceRelatedCaches($workspace);

        $workspace->delete();

        return redirect()->route('space.index')
            ->with('success', 'Workspace deleted successfully!');
    }
}