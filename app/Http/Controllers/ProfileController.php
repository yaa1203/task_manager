<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
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

    /**
     * Tampilkan halaman profil user.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /**
     * Update profil user (nama & email).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $user->update($request->only('name', 'email'));

        return redirect()->route('profile.edit')->with('status', 'Profil berhasil diperbarui!');
    }

    /**
     * Ubah password user.
     */
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

    /**
     * Hapus akun user.
     */
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

    /**
     * Tampilkan halaman profil admin.
     */
    public function profileAdmin(Request $request): View
    {
        return view('admin.profil.index', ['admin' => Auth::user()]);
    }

    /**
     * Update profil admin (nama &  email).
     */
    public function updateAdmin(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        if ($user->email !== $validated['email'] && $user instanceof MustVerifyEmail) {
            $user->email_verified_at = null;
        }

        $user->fill($validated)->save();

        return redirect()->route('admin.profile')->with('status', 'Profil admin berhasil diperbarui!');
    }

    /**
     * Ubah password admin.
     */
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

    /**
     * Hapus akun admin.
     */
    public function destroyAdmin(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Kata sandi wajib diisi untuk menghapus akun.',
            'password.current_password' => 'Kata sandi yang Anda masukkan salah.',
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Akun admin berhasil dihapus.');
    }

    /**
     * Kirim ulang link verifikasi email admin.
     */
    public function sendVerificationAdmin(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('admin.profile')->with('status', 'Email sudah terverifikasi.');
        }

        $request->user()->sendEmailVerificationNotification();

        return redirect()->route('admin.profile')->with('status', 'Link verifikasi telah dikirim ke email Anda!');
    }
}
