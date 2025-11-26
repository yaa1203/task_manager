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
        $requests = PasswordResetRequest::with('user')->orderBy('created_at', 'desc')->get();
        return view('superadmin.reset-requests.index', compact('requests'));
    }

    public function reset($id)
    {
        $req = PasswordResetRequest::findOrFail($id);

        $user = $req->user;

        $newPassword = Str::random(10);

        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        $req->update(['status' => 'done']);

        return back()->with('new_password', "Password baru untuk {$user->email}: {$newPassword}");
    }
}
