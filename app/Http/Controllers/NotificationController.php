<?php

// app/Http/Controllers/NotificationController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all'); // all, unread, read
        
        // Query notifikasi berdasarkan filter
        $query = $user->notifications()->orderBy('created_at', 'desc');
        
        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }
        
        $notifications = $query->paginate(15)->appends(['filter' => $filter]);
        
        return view('notifikasi.index', compact('notifications', 'filter'));
    }
    
    public function markAsRead(DatabaseNotification $notification)
    {
        // Pastikan notifikasi milik user yang sedang login
        if ($notification->notifiable_id != Auth::id() || $notification->notifiable_type != get_class(Auth::user())) {
            abort(403);
        }
        
        $notification->markAsRead();
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Notifikasi telah ditandai sebagai dibaca.');
    }
    
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}