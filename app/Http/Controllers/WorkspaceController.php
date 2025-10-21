<?php
namespace App\Http\Controllers;

use App\Models\Workspace;
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

<<<<<<< HEAD
        \Log::info('Request all data:', $request->all());

    // ✅ Gabungkan tanggal dan waktu sebelum validasi
    if ($request->filled('due_date_date') && $request->filled('due_date_time')) {
        $request->merge([
            'due_date' => $request->due_date_date . ' ' . $request->due_date_time . ':00'
        ]);
=======
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

        // Determine user IDs based on assign_to_all flag
        if ($request->assign_to_all) {
            $userIds = User::where('role', 'user')->pluck('id')->toArray();
        } else {
            $userIds = $validated['user_ids'];
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            if ($file->isValid()) {
                $filePath = $file->store('task_files', 'public');
            }
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                $filePath = $file->store('task_files', 'public');
                $originalFilename = $file->getClientOriginalName();
                
                // Simpan nama asli
                $task->update(['original_filename' => $originalFilename]);
            }
        }

        // Create single task
        $task = Task::create([
            'workspace_id' => $workspace->id,
            'created_by' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
            'link' => $validated['link'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
        ]);

        // Attach users to task
        $task->assignedUsers()->attach($userIds);

        // Send notifications to all assigned users
        foreach ($userIds as $userId) {
            $assignedUser = User::find($userId);
            if ($assignedUser) {
                $assignedUser->notify(new TaskAssignedNotification($task));
            }
        }

        $userCount = count($userIds);
        return redirect()->route('workspaces.show', $workspace)
            ->with('success', "Task created and assigned to {$userCount} user(s) successfully!");
>>>>>>> 1bd569bbe245aeb3cd60c14f241eff0fe762c5fd
    }

    // ✅ Validasi setelah due_date digabungkan
    $validated = $request->validate([
        'assign_to_all' => 'nullable|boolean',
        'user_ids' => 'required_without:assign_to_all|array|min:1',
        'user_ids.*' => 'exists:users,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:todo,in_progress,done',
        'priority' => 'required|in:low,medium,high,urgent',
        'due_date' => 'nullable|date_format:Y-m-d H:i:s', // Format wajib Y-m-d H:i:s
        'file' => 'nullable|file|max:10240',
        'link' => 'nullable|url',
    ]);

    // Debug log untuk memastikan data masuk dengan benar
    \Log::info('Due Date After Merge:', ['due_date' => $request->due_date]);

    // Tentukan user IDs
    if ($request->assign_to_all) {
        $userIds = User::where('role', 'user')->pluck('id')->toArray();
    } else {
        $userIds = $validated['user_ids'];
    }

    // Handle upload file
    $filePath = null;
    if ($request->hasFile('file')) {
        $file = $request->file('file');

        if ($file->isValid()) {
            $filePath = $file->store('task_files', 'public');
            \Log::info('File uploaded successfully', [
                'path' => $filePath,
                'full_path' => storage_path('app/public/' . $filePath),
                'exists' => file_exists(storage_path('app/public/' . $filePath))
            ]);
        } else {
            \Log::error('File upload failed - invalid file');
            return back()->withErrors(['file' => 'File upload failed. Please try again.']);
        }
    }

    // Buat task
    $task = Task::create([
        'workspace_id' => $workspace->id,
        'created_by' => auth()->id(),
        'title' => $validated['title'],
        'description' => $validated['description'] ?? null,
        'file_path' => $filePath,
        'link' => $validated['link'] ?? null,
        'status' => $validated['status'],
        'priority' => $validated['priority'],
        'due_date' => $validated['due_date'] ?? null, // Sudah digabung
    ]);

    \Log::info('Task Created:', [
        'task_id' => $task->id,
        'due_date_saved' => $task->due_date
    ]);

    // Attach user ke task
    $task->assignedUsers()->attach($userIds);

    // Kirim notifikasi
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
            'due_date' => 'nullable|date_format:Y-m-d H:i:s',
            'file' => 'nullable|file|max:10240',
            'link' => 'nullable|url',
            'remove_file' => 'nullable|boolean',
        ]);

        // Handle file upload
        $filePath = $task->file_path;
        
        // Remove old file if requested
        if ($request->remove_file && $task->file_path) {
            Storage::disk('public')->delete($task->file_path);
            $filePath = null;
        }
        
        // Upload new file if provided
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($task->file_path) {
                Storage::disk('public')->delete($task->file_path);
            }
            $filePath = $request->file('file')->store('task_files', 'public');
        }

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($task->file_path) {
                Storage::disk('public')->delete($task->file_path);
            }
            
            $filePath = $request->file('file')->store('task_files', 'public');
            $originalFilename = $request->file('file')->getClientOriginalName();
            
            // Update task dengan nama asli
            $task->update([
                'file_path' => $filePath,
                'original_filename' => $originalFilename
            ]);
        }

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
            'link' => $validated['link'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
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

        // Load task dengan data lengkap
        $task->load(['assignedUsers', 'creator']);
        
        $submissions = $task->submissions()
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $hasSubmitted = $submissions->isNotEmpty();

        return view('work.tasks.show', compact('workspace', 'task', 'submissions', 'hasSubmitted'));
    }

    /**
     * User submit task - FIXED VERSION
     * Ganti method submitTask di WorkspaceController dengan ini
     */
    public function submitTask(Request $request, Workspace $workspace, Task $task)
    {
        // Cek apakah user diizinkan mengakses task ini
        $isAccessible = $task->assignedUsers()
            ->where('user_id', Auth::id())
            ->exists();

        abort_unless($isAccessible, 403);

        // Cek apakah task ini milik workspace yang benar
        if ($task->workspace_id !== $workspace->id) {
            abort(404);
        }

        // Validasi input
        $request->validate([
            'file' => 'nullable|file|max:10240',
            'link' => 'nullable|url',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Buat array data submission dasar
        $submissionData = [
            'link' => $request->link,
            'notes' => $request->notes,
            'status' => 'pending',
            'submitted_at' => now(),
        ];
        
        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            if ($file->isValid()) {
                // PENTING: Simpan nama asli file
                $originalFilename = $file->getClientOriginalName();
                
                // Buat nama file unik untuk penyimpanan
                $extension = $file->getClientOriginalExtension();
                $filename = uniqid() . '_' . time() . '.' . $extension;
                
                // Simpan file ke direktori submissions
                $filePath = $file->storeAs('submissions', $filename, 'public');
                
                // PENTING: Tambahkan ke submission data
                $submissionData['file_path'] = $filePath;
                $submissionData['original_filename'] = $originalFilename;
                
                // Debug log untuk memastikan
                \Log::info('Submission Upload:', [
                    'original' => $originalFilename,
                    'stored' => $filePath,
                    'user' => Auth::id(),
                    'task' => $task->id
                ]);
            }
        }

        // Update atau create submission
        $submission = UserTaskSubmission::updateOrCreate(
            [
                'task_id' => $task->id,
                'user_id' => Auth::id(),
            ],
            $submissionData
        );

        // Verifikasi hasil save
        \Log::info('Submission Result:', [
            'id' => $submission->id,
            'original_filename_saved' => $submission->original_filename,
            'display_name' => $submission->display_name,
        ]);

        // Kirim notifikasi ke pemilik workspace
        $workspaceOwner = User::find($workspace->user_id);
        if ($workspaceOwner) {
            $workspaceOwner->notify(new \App\Notifications\TaskSubmittedNotification(
                $task,
                Auth::user(),
                $submission
            ));
        }

        return back()->with('success', 'Pengumpulan berhasil dikirim!');
    }

    /**
     * View task file in browser - UNTUK USER DAN ADMIN
     */
    public function viewTaskFile(Workspace $workspace, Task $task)
    {
        // Check authorization berdasarkan role
        if (Auth::user()->role === 'admin') {
            $this->authorize('view', $workspace);
        } else {
            // Untuk user biasa, cek apakah dia assigned ke task ini
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
        
        // Handle image files
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
        if (in_array($extension, $imageExtensions)) {
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($task->file_path) . '"'
            ]);
        }
        
        // Handle PDF files
        if ($extension === 'pdf') {
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($task->file_path) . '"'
            ]);
        }
        
        // Handle text files
        $textExtensions = ['txt', 'md', 'csv'];
        if (in_array($extension, $textExtensions)) {
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($task->file_path) . '"'
            ]);
        }
        
        // For other file types, return with appropriate headers for preview
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($task->file_path) . '"',
            'X-Content-Type-Options' => 'nosniff'
        ]);
    }

    /**
     * Download task file - UNTUK USER DAN ADMIN
     */
    public function downloadTaskFile(Workspace $workspace, Task $task)
    {
        // Check authorization berdasarkan role
        if (Auth::user()->role === 'admin') {
            $this->authorize('view', $workspace);
        } else {
            // Untuk user biasa, cek apakah dia assigned ke task ini
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
     * View submission file in browser
     */
    public function viewSubmissionFile(Workspace $workspace, Task $task, UserTaskSubmission $submission)
    {
        // Check if user is admin or the submission owner
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
     * Download submission file
     */
    public function downloadSubmissionFile(Workspace $workspace, Task $task, UserTaskSubmission $submission)
    {
        // Check if user is admin or the submission owner
        if (Auth::user()->role !== 'admin' && $submission->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$submission->file_path || !Storage::disk('public')->exists($submission->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($submission->file_path);
    }
}