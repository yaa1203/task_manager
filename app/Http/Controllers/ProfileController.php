<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * ====================================
     * BAGIAN USER
     * ====================================
     */

    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                $user->deleteAvatar();
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return redirect()->route('profile.edit')->with('status', 'Profil berhasil diperbarui!');
    }

    public function removeAvatarUser(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if ($user->avatar) {
            $user->deleteAvatar();
            $user->avatar = null;
            $user->save();
            
            return redirect()->route('profile.edit')->with('status', 'Foto profil berhasil dihapus!');
        }

        return redirect()->route('profile.edit')->with('error', 'Tidak ada foto profil untuk dihapus.');
    }

    public function updatePasswordUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.edit')->with('status', 'Kata sandi berhasil diperbarui.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('status', 'Akun Anda telah dihapus.');
    }

    /**
     * ====================================
     * BAGIAN ADMIN
     * ====================================
     */

    public function profileAdmin(Request $request): View
    {
        return view('admin.profil.index', ['admin' => Auth::user()]);
    }

    public function updateAdmin(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // max 2MB
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                $user->deleteAvatar();
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        if ($user->email !== $validated['email'] && $user instanceof MustVerifyEmail) {
            $user->email_verified_at = null;
        }

        $user->fill($validated)->save();

        return redirect()->route('admin.profile')->with('status', 'Profil admin berhasil diperbarui!');
    }

    public function removeAvatarAdmin(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if ($user->avatar) {
            $user->deleteAvatar();
            $user->avatar = null;
            $user->save();
            
            return redirect()->route('admin.profile')->with('status', 'Foto profil berhasil dihapus!');
        }

        return redirect()->route('admin.profile')->with('error', 'Tidak ada foto profil untuk dihapus.');
    }

    public function updatePasswordAdmin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini salah.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile')->with('status', 'Kata sandi admin berhasil diperbarui!');
    }

    public function destroyAdmin(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Kata sandi wajib diisi untuk menghapus akun.',
            'password.current_password' => 'Kata sandi yang Anda masukkan salah.',
        ]);

        $user = $request->user();

        // Delete avatar before deleting user
        if ($user->avatar) {
            $user->deleteAvatar();
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Akun admin berhasil dihapus.');
    }

    public function sendVerificationAdmin(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('admin.profile')->with('status', 'Email sudah terverifikasi.');
        }

        $request->user()->sendEmailVerificationNotification();

        return redirect()->route('admin.profile')->with('status', 'Link verifikasi telah dikirim ke email Anda!');
    }

    /**
     * ====================================
     * BAGIAN SUPER ADMIN
     * ====================================
     */

    public function profileSuperAdmin(Request $request): View
    {
        return view('superadmin.profiles.index', ['admin' => Auth::user()]);
    }

    public function updateSuperAdmin(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                $user->deleteAvatar();
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        if ($user->email !== $validated['email'] && $user instanceof MustVerifyEmail) {
            $user->email_verified_at = null;
        }

        $user->fill($validated)->save();

        return redirect()->route('superadmin.profile')->with('status', 'Profil super admin berhasil diperbarui!');
    }

    public function removeAvatarSuperAdmin(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if ($user->avatar) {
            $user->deleteAvatar();
            $user->avatar = null;
            $user->save();
            
            return redirect()->route('superadmin.profile')->with('status', 'Foto profil berhasil dihapus!');
        }

        return redirect()->route('superadmin.profile')->with('error', 'Tidak ada foto profil untuk dihapus.');
    }

    public function updatePasswordSuperAdmin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini salah.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('superadmin.profile')->with('status', 'Kata sandi super admin berhasil diperbarui!');
    }

    public function destroySuperAdmin(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Kata sandi wajib diisi untuk menghapus akun.',
            'password.current_password' => 'Kata sandi yang Anda masukkan salah.',
        ]);

        $user = $request->user();

        if ($user->avatar) {
            $user->deleteAvatar();
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Akun super admin berhasil dihapus.');
    }

    public function sendVerificationSuperAdmin(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('superadmin.profile')->with('status', 'Email sudah terverifikasi.');
        }

        $request->user()->sendEmailVerificationNotification();

        return redirect()->route('superadmin.profile')->with('status', 'Link verifikasi telah dikirim ke email Anda!');
    }
}