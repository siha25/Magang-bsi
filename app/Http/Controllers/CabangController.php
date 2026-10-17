<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCabangRequest;
use App\Http\Requests\UpdateCabangRequest;
use App\Models\Cabang;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    /**
     * Menampilkan daftar cabang dengan pencarian, filter, dan pagination.
     */
    public function index(Request $request): View
    {
        $cari = $request->string('cari')->trim()->toString();
        $status = $request->string('status')->trim()->toString();
        $wilayah = $request->string('wilayah')->trim()->toString();

        $query = Cabang::query();

        // Filter pencarian teks (kode, nama, wilayah, penanggung jawab)
        if ($cari !== '') {
            $query->search($cari);
        }

        // Filter status aktif/nonaktif
        if ($status === 'aktif') {
            $query->where('status_aktif', true);
        } elseif ($status === 'nonaktif') {
            $query->where('status_aktif', false);
        }

        // Filter spesifik wilayah
        if ($wilayah !== '') {
            $query->where('wilayah', $wilayah);
        }

        // Pagination untuk mendukung lebih dari 30 cabang (10 per halaman)
        $cabangs = $query->latest()->paginate(10)->withQueryString();

        // Statistik ringkas untuk kartu atas
        $stats = [
            'total' => Cabang::count(),
            'aktif' => Cabang::where('status_aktif', true)->count(),
            'nonaktif' => Cabang::where('status_aktif', false)->count(),
            'total_wilayah' => Cabang::whereNotNull('wilayah')->where('wilayah', '!=', '')->distinct()->count('wilayah'),
        ];

        // Daftar wilayah unik untuk dropdown filter
        $wilayahOptions = Cabang::query()
            ->whereNotNull('wilayah')
            ->where('wilayah', '!=', '')
            ->distinct()
            ->orderBy('wilayah')
            ->pluck('wilayah');

        return view('cabang.index', compact(
            'cabangs',
            'stats',
            'wilayahOptions',
            'cari',
            'status',
            'wilayah'
        ));
    }

    /**
     * Menampilkan formulir penambahan cabang baru.
     */
    public function create(): View
    {
        return view('cabang.create');
    }

    /**
     * Menyimpan data cabang baru ke database.
     */
    public function store(StoreCabangRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['status_aktif'] = $request->boolean('status_aktif', true);

        $cabang = Cabang::create($validated);

        return redirect()
            ->route('cabang.index')
            ->with('success', "Cabang '{$cabang->nama_cabang}' ({$cabang->kode_cabang}) berhasil ditambahkan.");
    }

    /**
     * Menampilkan formulir edit data cabang.
     */
    public function edit(Cabang $cabang): View
    {
        return view('cabang.edit', compact('cabang'));
    }

    /**
     * Memperbarui data cabang yang sudah ada.
     */
    public function update(UpdateCabangRequest $request, Cabang $cabang): RedirectResponse
    {
        $validated = $request->validated();
        $validated['status_aktif'] = $request->boolean('status_aktif', false);

        $cabang->update($validated);

        return redirect()
            ->route('cabang.index')
            ->with('success', "Perubahan data cabang '{$cabang->nama_cabang}' berhasil disimpan.");
    }

    /**
     * Mengaktifkan atau menonaktifkan status cabang.
     */
    public function toggleStatus(Cabang $cabang): RedirectResponse
    {
        $statusBaru = ! $cabang->status_aktif;
        $cabang->update(['status_aktif' => $statusBaru]);

        $statusTeks = $statusBaru ? 'diaktifkan kembali' : 'dinonaktifkan';

        return redirect()
            ->back()
            ->with('success', "Cabang '{$cabang->nama_cabang}' berhasil {$statusTeks}.");
    }
}
