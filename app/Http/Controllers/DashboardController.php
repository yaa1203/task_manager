<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Statistik task
        $tasksCount = Task::where('user_id', $userId)->count();
        $tasksTodo = Task::where('user_id', $userId)->where('status', 'todo')->count();
        $tasksInProgress = Task::where('user_id', $userId)->where('status', 'in_progress')->count();
        $tasksDone = Task::where('user_id', $userId)->where('status', 'done')->count();

        // Statistik project
        $projectsCount = Project::where('user_id', $userId)->count();

        // Notifikasi terbaru (5)
        $notifications = Auth::user()->notifications()->latest()->take(5)->get();

        return view('dashboard', compact(
            'tasksCount',
            'tasksTodo',
            'tasksInProgress',
            'tasksDone',
            'projectsCount',
            'notifications'
        ));
    }

    public function AdminIndex()
    {
        return view('admin.dashboard', [
            'totalUsers' => User::where('role', 'user')->count(), // hanya user
            'totalProjects' => Project::count(),
            'totalTasks' => Task::count(),
            'admins' => User::where('role', 'admin')->get(),
            'users' => User::where('role', 'user')->latest()->take(5)->get(),
        ]);
    }
}
