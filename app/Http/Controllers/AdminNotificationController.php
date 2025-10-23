<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    /**
     * Tampilkan semua notifikasi untuk admin yang login
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'Akses ditolak');
        }

        // Ambil notifikasi terbaru, pagination 10
        $notifications = $user->notifications()->latest()->paginate(10);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'Akses ditolak');
        }

        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi berhasil ditandai sudah dibaca.');
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'Akses ditolak');
        }

        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi berhasil ditandai sudah dibaca.');
    }
}
