<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAsramaRequest;
use App\Http\Requests\UpdateAsramaRequest;
use App\Models\Asrama;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AsramaController extends Controller
{
    /**
     * Menampilkan daftar asrama dengan pencarian, filter, dan pagination.
     */
    public function index(Request $request): View
    {
        $cari = $request->string('cari')->trim()->toString();
        $status = $request->string('status')->trim()->toString();
        $wilayah = $request->string('wilayah')->trim()->toString();

        $query = Asrama::query();

        if ($cari !== '') {
            $query->search($cari);
        }

        if ($status === 'aktif') {
            $query->where('status_aktif', true);
        } elseif ($status === 'nonaktif') {
            $query->where('status_aktif', false);
        }

        if ($wilayah !== '') {
            $query->where('wilayah', $wilayah);
        }

        $asramas = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => Asrama::count(),
            'aktif' => Asrama::where('status_aktif', true)->count(),
            'nonaktif' => Asrama::where('status_aktif', false)->count(),
            'total_wilayah' => Asrama::whereNotNull('wilayah')->where('wilayah', '!=', '')->distinct()->count('wilayah'),
        ];

        $wilayahOptions = Asrama::query()
            ->whereNotNull('wilayah')
            ->where('wilayah', '!=', '')
            ->distinct()
            ->orderBy('wilayah')
            ->pluck('wilayah');

        return view('asrama.index', compact(
            'asramas',
            'stats',
            'wilayahOptions',
            'cari',
            'status',
            'wilayah'
        ));
    }

    /**
     * Menampilkan formulir penambahan asrama baru.
     */
    public function create(): View
    {
        return view('asrama.create');
    }

    /**
     * Menyimpan data asrama baru ke database.
     */
    public function store(StoreAsramaRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['status_aktif'] = $request->boolean('status_aktif', true);

        $asrama = Asrama::create($validated);

        return redirect()
            ->route('asrama.index')
            ->with('success', "Asrama '{$asrama->nama_asrama}' ({$asrama->kode_asrama}) berhasil ditambahkan.");
    }

    /**
     * Menampilkan formulir edit data asrama.
     */
    public function edit(Asrama $asrama): View
    {
        return view('asrama.edit', compact('asrama'));
    }

    /**
     * Memperbarui data asrama yang sudah ada.
     */
    public function update(UpdateAsramaRequest $request, Asrama $asrama): RedirectResponse
    {
        $validated = $request->validated();
        $validated['status_aktif'] = $request->boolean('status_aktif', false);

        $asrama->update($validated);

        return redirect()
            ->route('asrama.index')
            ->with('success', "Perubahan data asrama '{$asrama->nama_asrama}' berhasil disimpan.");
    }

    /**
     * Mengaktifkan atau menonaktifkan status asrama.
     */
    public function toggleStatus(Asrama $asrama): RedirectResponse
    {
        $statusBaru = ! $asrama->status_aktif;
        $asrama->update(['status_aktif' => $statusBaru]);

        $statusTeks = $statusBaru ? 'diaktifkan kembali' : 'dinonaktifkan';

        return redirect()
            ->back()
            ->with('success', "Asrama '{$asrama->nama_asrama}' berhasil {$statusTeks}.");
    }
}
