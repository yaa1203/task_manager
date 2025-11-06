<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Kalau belum login, redirect ke login
        if (!$user) {
            return redirect()->route('login');
        }

        // Cek apakah role user cocok dengan salah satu role yang diizinkan
        if (!in_array($user->role, $roles)) {
            // Kalau tidak cocok, arahkan ke dashboard sesuai rolenya
            return match ($user->role) {
                'superadmin' => redirect()->route('superadmin.dashboard'),
                'admin'      => redirect()->route('admin.dashboard'),
                default      => redirect()->route('dashboard'),
            };
        }

        return $next($request);
    }
}
