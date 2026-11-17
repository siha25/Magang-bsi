<?php

// BAGIAN: PENGURUS PUSAT - Profil Akun Admin Pendik

namespace App\Pusat\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfilController extends Controller
{
    /**
     * Menampilkan halaman profil akun Admin Pendik.
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

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

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verifikasi password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => __('Password saat ini salah.'),
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('pusat.profil.index')
            ->with('success', __('Password berhasil diubah.'));
    }
}
