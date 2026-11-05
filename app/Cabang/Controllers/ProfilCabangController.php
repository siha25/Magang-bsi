<?php

// BAGIAN: PENGURUS CABANG - Pengelolaan Profil Cabang

namespace App\Cabang\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asrama;
use App\Shared\Auth\CabangSession;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfilCabangController extends Controller
{
    /**
     * Menampilkan halaman rincian profil cabang yang sedang login.
     */
    public function index(): View
    {
        $cabangId = CabangSession::getId();
        $cabang = $cabangId ? Asrama::find($cabangId) : auth()->user()?->asrama;

        abort_if(! $cabang, 404, 'Data cabang tidak ditemukan.');

        return view('cabang.views.profil.index', compact('cabang'));
    }

    /**
     * Memperbarui informasi profil cabang.
     */
    public function update(Request $request): RedirectResponse
    {
        $cabangId = CabangSession::getId();
        $cabang = $cabangId ? Asrama::find($cabangId) : auth()->user()?->asrama;

        abort_if(! $cabang, 404, 'Data cabang tidak ditemukan.');

        $validated = $request->validate([
            'nama_asrama' => ['required', 'string', 'max:255'],
            'wilayah' => ['required', 'string', 'max:100'],
            'alamat' => ['nullable', 'string', 'max:1000'],
            'nomor_telepon' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'string', 'email', 'max:150'],
            'penanggung_jawab' => ['required', 'string', 'max:150'],
            'jam_operasional' => ['nullable', 'string', 'max:100'],
        ], [
            'nama_asrama.required' => 'Nama cabang wajib diisi.',
            'wilayah.required' => 'Kota / Wilayah wajib diisi.',
            'penanggung_jawab.required' => 'Nama kepala cabang / penanggung jawab wajib diisi.',
            'email.email' => 'Format email cabang tidak valid.',
        ]);

        $cabang->update($validated);

        // Segarkan informasi di sesi cabang
        CabangSession::refresh();

        return redirect()->route('cabang.profil.index')
            ->with('success', __('Profil cabang berhasil diperbarui.'));
    }
}

