<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AdminNotification;

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

    public function adminIndex()
    {
        $tasks = Task::with(['user', 'project'])->latest()->paginate(15);
        return view('admin.tugas.index', compact('tasks'));
    }

    // Method untuk admin membuat tugas untuk user
     public function adminCreate()
    {
        $users = User::where('role', 'user')->get(); // Mendapatkan semua user
        
        return view('admin.tugas.create', compact('users'));
    }

    // Method untuk menyimpan tugas yang dibuat oleh admin
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array|min:1', // Mengubah menjadi array
            'user_ids.*' => 'exists:users,id', // Validasi setiap ID user
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'assign_to_all' => 'nullable|boolean', // Untuk fitur assign ke semua user
        ]);

        // Pastikan user yang login adalah admin
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('tugas.index')->with('error', 'Unauthorized action.');
        }

        // Jika assign ke semua user, ambil semua user dengan role user
        if ($request->assign_to_all) {
            $userIds = User::where('role', 'user')->pluck('id')->toArray();
        } else {
            $userIds = $validated['user_ids'];
        }

        // Buat tugas untuk setiap user yang dipilih
        $tasks = [];
        foreach ($userIds as $userId) {
            $task = Task::create([
                'user_id' => $userId,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'priority' => $validated['priority'],
                'due_date' => $validated['due_date'],
                'created_by' => auth()->id(),
            ]);
            
            $tasks[] = $task;
            
            // Notifikasi ke user yang ditugaskan
            $assignedUser = User::find($userId);
            if ($assignedUser) {
                $assignedUser->notify(new AdminNotification(
                    'New Task Assigned',
                    'You have been assigned a new task: ' . $task->title,
                    route('tasks.show', $task)
                ));
            }
        }

        return redirect()->route('tugas.index')->with('success', count($tasks) . ' task(s) created successfully and assigned to user(s).');
    }
    
    // Method untuk pencarian tugas
    public function adminSearch(Request $request)
    {
        $query = $request->input('query');
        
        $tasks = Task::with(['user'])
            ->where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhereHas('user', function($u) use ($query) {
                      $u->where('name', 'like', '%' . $query . '%')
                        ->orWhere('email', 'like', '%' . $query . '%');
                  });
            })
            ->latest()
            ->paginate(15);
            
        return view('admin.tugas.index', compact('tasks', 'query'));
    }

    public function adminShow(Task $task)
    {
        $task->load('project');
        return view('admin.tugas.show', compact('task'));
    }

    public function adminDestroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tugas.index')->with('success', 'Task deleted by Admin.');
    }
}