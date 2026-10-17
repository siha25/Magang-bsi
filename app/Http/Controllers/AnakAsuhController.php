<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnakAsuhRequest;
use App\Http\Requests\UpdateAnakAsuhRequest;
use App\Models\AnakAsuh;
use App\Models\Asrama;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnakAsuhController extends Controller
{
    /**
     * Menampilkan daftar anak asuh dengan pencarian, filter, dan pagination.
     */
    public function index(Request $request): View
    {
        $cari = $request->string('cari')->trim()->toString();
        $asramaId = $request->input('asrama_id');
        $jenjang = $request->string('jenjang')->trim()->toString();
        $status = $request->string('status')->trim()->toString();

        $query = AnakAsuh::query()->with('asrama');

        if ($cari !== '') {
            $query->search($cari);
        }

        if (! empty($asramaId)) {
            $query->where('asrama_id', $asramaId);
        } else {
            $asramaId = '';
        }

        if (in_array($jenjang, ['SD', 'SMP', 'SMA'], true)) {
            $query->where('jenjang_pendidikan', $jenjang);
        } else {
            $jenjang = '';
        }

        if ($status === 'aktif') {
            $query->where('status_aktif', true);
        } elseif ($status === 'nonaktif') {
            $query->where('status_aktif', false);
        } else {
            $status = '';
        }

        $anakAsuhs = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => AnakAsuh::count(),
            'aktif' => AnakAsuh::where('status_aktif', true)->count(),
            'nonaktif' => AnakAsuh::where('status_aktif', false)->count(),
            'total_sd' => AnakAsuh::where('jenjang_pendidikan', 'SD')->count(),
            'total_smp' => AnakAsuh::where('jenjang_pendidikan', 'SMP')->count(),
            'total_sma' => AnakAsuh::where('jenjang_pendidikan', 'SMA')->count(),
        ];

        $asramas = Asrama::orderBy('nama_asrama')->get(['id', 'nama_asrama', 'kode_asrama']);

        return view('anak-asuh.index', compact(
            'anakAsuhs',
            'stats',
            'asramas',
            'cari',
            'asramaId',
            'jenjang',
            'status'
        ));
    }

    /**
     * Menampilkan formulir pendaftaran anak asuh baru.
     */
    public function create(): View
    {
        // Hanya asrama dengan status aktif yang diizinkan untuk pendaftaran baru
        $asramas = Asrama::where('status_aktif', true)
            ->orderBy('nama_asrama')
            ->get();

        return view('anak-asuh.create', compact('asramas'));
    }

    /**
     * Menyimpan data anak asuh baru ke database.
     */
    public function store(StoreAnakAsuhRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['status_aktif'] = $request->boolean('status_aktif', true);

        $anakAsuh = AnakAsuh::create($validated);

        return redirect()
            ->route('anak-asuh.index')
            ->with('success', "Data anak asuh '{$anakAsuh->nama_lengkap}' ({$anakAsuh->nomor_induk}) berhasil ditambahkan.");
    }

    /**
     * Menampilkan formulir edit data anak asuh.
     */
    public function edit(AnakAsuh $anakAsuh): View
    {
        // Mengizinkan asrama aktif DAN asrama yang sedang terhubung pada anak asuh ini
        $asramas = Asrama::where('status_aktif', true)
            ->orWhere('id', $anakAsuh->asrama_id)
            ->orderBy('nama_asrama')
            ->get();

        return view('anak-asuh.edit', compact('anakAsuh', 'asramas'));
    }

    /**
     * Memperbarui data anak asuh di database.
     */
    public function update(UpdateAnakAsuhRequest $request, AnakAsuh $anakAsuh): RedirectResponse
    {
        $validated = $request->validated();
        $validated['status_aktif'] = $request->boolean('status_aktif', false);

        $anakAsuh->update($validated);

        return redirect()
            ->route('anak-asuh.index')
            ->with('success', "Perubahan data anak asuh '{$anakAsuh->nama_lengkap}' berhasil disimpan.");
    }

    /**
     * Mengaktifkan atau menonaktifkan status anak asuh via metode PATCH.
     */
    public function toggleStatus(AnakAsuh $anakAsuh): RedirectResponse
    {
        $statusBaru = ! $anakAsuh->status_aktif;
        $anakAsuh->update(['status_aktif' => $statusBaru]);

        $statusTeks = $statusBaru ? 'diaktifkan kembali' : 'dinonaktifkan';

        return redirect()
            ->back()
            ->with('success', "Status anak asuh '{$anakAsuh->nama_lengkap}' berhasil {$statusTeks}.");
    }
}
