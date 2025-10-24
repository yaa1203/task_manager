<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 🔐 Autentikasi user
        $request->authenticate();

        // 🔄 Regenerasi session agar aman (anti session fixation)
        $request->session()->regenerate();

        // 🚀 Redirect sesuai role user
        $user = Auth::user();

        return match ($user->role) {
            'superadmin' => redirect()->intended(route('superadmin.dashboard', absolute: false)),
            'admin'      => redirect()->intended(route('admin.dashboard', absolute: false)),
            default      => redirect()->intended(route('dashboard', absolute: false)), // user biasa
        };
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
