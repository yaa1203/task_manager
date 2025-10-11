<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Statistik task - menggunakan scope assignedTo (lebih bersih)
        $tasksCount = Task::assignedTo($userId)->count();
        $tasksTodo = Task::assignedTo($userId)->where('status', 'todo')->count();
        $tasksInProgress = Task::assignedTo($userId)->where('status', 'in_progress')->count();
        $tasksDone = Task::assignedTo($userId)->where('status', 'done')->count();

        // Notifikasi terbaru (5)
        $notifications = Auth::user()->notifications()->latest()->take(5)->get();

        return view('dashboard', compact(
            'tasksCount',
            'tasksTodo',
            'tasksInProgress',
            'tasksDone',
            'notifications'
        ));
    }

    public function AdminIndex()
    {
        return view('admin.dashboard', [
            'totalUsers' => User::where('role', 'user')->count(),
            'totalTasks' => Task::count(),
            'admins' => User::where('role', 'admin')->get(),
            'users' => User::where('role', 'user')->latest()->take(5)->get(),
        ]);
    }
}