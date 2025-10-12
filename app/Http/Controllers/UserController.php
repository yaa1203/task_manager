<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Tampilkan semua user untuk monitoring (hanya role 'user')
     */
    public function index()
    {
        // Ambil hanya user dengan role 'user', tidak termasuk admin
        $users = User::where('role', 'user')
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
        // Jika ya, redirect dengan pesan error (opsional, untuk keamanan tambahan)
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
                'assignedUsers'
            ])
            ->latest()
            ->get();

        return view('admin.users.show', compact('user', 'tasks'));
    }
}