<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminResetController extends Controller
{
    public function index()
    {
        // 👇 Eager load user dan admin
        $requests = PasswordResetRequest::with(['user', 'admin'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('superadmin.reset-requests.index', compact('requests'));
    }

    public function reset($id)
    {
        $req = PasswordResetRequest::with('user')->findOrFail($id);

        $user = $req->user;

        // Generate password baru
        $newPassword = Str::random(10);

        // Update password user
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        // 👇 Update status dan simpan admin_id
        $req->update([
            'status' => 'done',
            'admin_id' => auth()->id(), // Simpan ID admin yang melakukan reset
        ]);

        // 👇 Kirim data ke session untuk ditampilkan di view
        return back()->with([
            'new_password' => $newPassword,
            'reset_request_id' => $req->id, // Untuk identifikasi request
            'user_email' => $user->email,
        ]);
    }
    // Di SuperAdminController.php atau controller yang relevan
    public function getResetRequestsCount()
    {
        return PasswordResetRequest::where('status', 'pending')->count();
    }
}