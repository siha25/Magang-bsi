<?php

// BAGIAN: PENGURUS PUSAT - Profil Akun Admin Pendik

namespace App\Pusat\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProfilController extends Controller
{
    /**
     * Menampilkan halaman profil akun Admin Pendik / Pengurus Pusat.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('pusat.views.profil.index', [
            'user' => $user,
        ]);
    }

    /**
     * Memperbarui informasi profil (nama, email).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ], [
            'name.required' => __('Nama lengkap wajib diisi.'),
            'name.max' => __('Nama lengkap maksimal 255 karakter.'),
            'email.required' => __('Alamat email wajib diisi.'),
            'email.email' => __('Format alamat email tidak valid.'),
            'email.unique' => __('Alamat email sudah digunakan oleh akun lain.'),
        ]);

        $user->update([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
        ]);

        return redirect()
            ->route('pusat.profil.index')
            ->with('success', __('Profil berhasil diperbarui.'));
    }

    /**
     * Mengubah password pengguna.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => __('Password saat ini wajib diisi.'),
            'password.required' => __('Password baru wajib diisi.'),
            'password.min' => __('Password baru minimal 8 karakter.'),
            'password.confirmed' => __('Konfirmasi password baru tidak cocok.'),
        ]);

        // Verifikasi password saat ini
        if (! Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => __('Password saat ini salah.'),
            ]);
        }

        $payload = [
            'password' => Hash::make($validated['password']),
        ];

        try {
            if (Schema::hasColumn('users', 'encrypted_password')) {
                $payload['encrypted_password'] = $validated['password'];
            }
        } catch (Throwable) {
            // Abaikan jika pengecekan kolom gagal
        }

        // Update password
        $user->update($payload);

        return redirect()
            ->route('pusat.profil.index')
            ->with('success', __('Password berhasil diubah.'));
    }
}
