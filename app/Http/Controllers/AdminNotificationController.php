<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    /**
     * Tampilkan semua notifikasi untuk admin yang login
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'Akses ditolak');
        }

        // Ambil filter dari query parameter
        $filter = $request->get('filter', 'all'); // all, unread, read

        // Query notifikasi berdasarkan filter
        $query = $user->notifications()->latest();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        // Pagination dengan filter parameter
        $notifications = $query->paginate(10)->appends(['filter' => $filter]);
        
        // Hitung statistik (selalu dari semua data, bukan dari paginated)
        $unreadCount = $user->unreadNotifications()->count();
        $readCount = $user->notifications()->whereNotNull('read_at')->count();
        $totalCount = $user->notifications()->count();

        return view('admin.notifications.index', compact(
            'notifications', 
            'unreadCount', 
            'readCount', 
            'totalCount',
            'filter'
        ));
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

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

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