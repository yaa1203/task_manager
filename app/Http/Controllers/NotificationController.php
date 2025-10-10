<?php

// app/Http/Controllers/NotificationController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Mengambil notifikasi yang belum dibaca terlebih dahulu
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('notifikasi.index', compact('notifications'));
    }
    
    public function show(DatabaseNotification $notification)
    {
        // Pastikan notifikasi milik user yang sedang login
        if ($notification->notifiable_id != Auth::id() || $notification->notifiable_type != get_class(Auth::user())) {
            abort(403);
        }
        
        // Tandai notifikasi sebagai sudah dibaca
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }
        
        // Tampilkan notifikasi
        return view('notifikasi.show', compact('notification'));
    }
    
    public function markAsRead(DatabaseNotification $notification)
    {
        // Pastikan notifikasi milik user yang sedang login
        if ($notification->notifiable_id != Auth::id() || $notification->notifiable_type != get_class(Auth::user())) {
            abort(403);
        }
        
        $notification->markAsRead();
        
        return back()->with('success', 'Notification marked as read.');
    }
    
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        
        return back()->with('success', 'All notifications marked as read.');
    }
}