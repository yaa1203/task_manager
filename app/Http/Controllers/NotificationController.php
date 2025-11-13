<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');
        
        $query = $user->notifications()->orderBy('created_at', 'desc');
        
        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }
        
        $notifications = $query->paginate(15)->appends(['filter' => $filter]);
        
        return view('notifikasi.index', compact('notifications', 'filter'));
    }
    
    public function markAsRead($id)
    {
        try {
            $notification = DatabaseNotification::findOrFail($id);
            
            // Pastikan notifikasi milik user yang sedang login
            if ($notification->notifiable_id != Auth::id()) {
                return back()->with('error', 'Akses ditolak.');
            }
            
            // Tandai sebagai dibaca
            $notification->markAsRead();
            
            return back()->with('success', 'Notifikasi berhasil ditandai sebagai dibaca.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menandai notifikasi sebagai dibaca.');
        }
    }
    
    public function markAllAsRead()
    {
        try {
            Auth::user()->unreadNotifications()->update(['read_at' => now()]);
            
            return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menandai semua notifikasi sebagai dibaca.');
        }
    }
}