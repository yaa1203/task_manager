<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Tampilkan semua user untuk monitoring (hanya role 'user' yang pernah diberi tugas)
     */
    public function index()
    {
        // Ambil hanya user dengan role 'user' yang pernah di-assign ke task
        // menggunakan whereHas untuk filter user yang memiliki assignedTasks
        $users = User::where('role', 'user')
            ->whereHas('assignedTasks') // Filter: hanya user yang pernah diberi tugas
            ->withCount('assignedTasks') // Hitung jumlah task yang di-assign
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        // Jangan izinkan admin menghapus diri sendiri
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        // Jangan izinkan menghapus user dengan role admin
        if ($user->role === 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Tidak diizinkan menghapus admin dari halaman ini.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Tampilkan detail user
     */
    public function show(User $user)
    {
        // Cek apakah user yang akan ditampilkan adalah admin
        if ($user->role === 'admin' && auth()->user()->role !== 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Akses ditolak.');
        }

        // Ambil semua task yang di-assign ke user ini
        // dengan relasi workspace dan submissions
        $tasks = $user->assignedTasks()
            ->with([
                'workspace',
                'submissions' => function($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
                'assignedUsers',
                'creator'
            ])
            ->latest()
            ->get();

        // Hitung statistik menggunakan accessor dari model
        $totalTasks = $user->total_assigned_tasks;
        $completedTasks = $user->completed_tasks_count;
        $pendingTasks = $user->pending_tasks_count;
        $todoTasks = $user->todo_tasks_count;

        return view('admin.users.show', compact('user', 'tasks', 'totalTasks', 'completedTasks', 'pendingTasks', 'todoTasks'));
    }
}