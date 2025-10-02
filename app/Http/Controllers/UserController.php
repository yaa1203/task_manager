<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Tampilkan semua user untuk monitoring
     */
    public function index()
    {
        // Ambil semua user terbaru, 15 per halaman
        $users = User::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        // Jangan izinkan admin menghapus diri sendiri
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function show(User $user)
    {
        // Pastikan relasi user dengan project dan task sudah ada di model
        // Contoh: User hasMany Projects, User hasMany Tasks
        $projects = $user->projects()->latest()->get();
        $tasks = $user->tasks()->latest()->get();

        return view('admin.users.show', compact('user', 'projects', 'tasks'));
    }
}
