<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnakAsuhRequest;
use App\Http\Requests\UpdateAnakAsuhRequest;
use App\Models\AnakAsuh;
use App\Models\Asrama;
use App\Services\NomorIndukGenerator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AnakAsuhController extends Controller
{
    /**
     * Menampilkan daftar anak asuh dengan pencarian, filter, dan pagination.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', AnakAsuh::class);

        $cari = $request->string('cari')->trim()->toString();
        $asramaId = $request->input('asrama_id');
        $jenjang = $request->string('jenjang')->trim()->toString();
        $status = $request->string('status')->trim()->toString();
        $isAdminCabang = $request->user()->isAdminCabang();
        $assignedAsramaId = $isAdminCabang ? $request->user()->asrama_id : null;

        $query = AnakAsuh::query()->with('asrama');

        if ($isAdminCabang) {
            $asramaId = $assignedAsramaId;
            $query->where('asrama_id', $assignedAsramaId ?? 0);
        }

        if ($cari !== '') {
            $query->search($cari);
        }

        if (! empty($asramaId) && ! $isAdminCabang) {
            $query->where('asrama_id', $asramaId);
        } elseif (! $isAdminCabang) {
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

        $statsQuery = AnakAsuh::query()->when(
            $isAdminCabang,
            fn ($query) => $query->where('asrama_id', $assignedAsramaId ?? 0)
        );
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'aktif' => (clone $statsQuery)->where('status_aktif', true)->count(),
            'nonaktif' => (clone $statsQuery)->where('status_aktif', false)->count(),
            'total_sd' => (clone $statsQuery)->where('jenjang_pendidikan', 'SD')->count(),
            'total_smp' => (clone $statsQuery)->where('jenjang_pendidikan', 'SMP')->count(),
            'total_sma' => (clone $statsQuery)->where('jenjang_pendidikan', 'SMA')->count(),
        ];

        $asramas = Asrama::query()
            ->when($isAdminCabang, fn ($query) => $query->where('id', $assignedAsramaId ?? 0))
            ->orderBy('nama_asrama')
            ->get(['id', 'nama_asrama', 'kode_asrama']);

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
        $this->authorize('create', AnakAsuh::class);

        $asramas = Asrama::where('status_aktif', true)
            ->orderBy('nama_asrama')
            ->get();

        return view('anak-asuh.create', compact('asramas'));
    }

    /**
     * Endpoint API pratinjau nomor induk berikutnya (AJAX).
     */
    public function previewNomorInduk(Request $request, NomorIndukGenerator $generator): JsonResponse
    {
        $this->authorize('create', AnakAsuh::class);

        $validated = $request->validate([
            'asrama_id' => ['required', 'integer', 'exists:asrama,id'],
            'tanggal_masuk' => ['required', 'date'],
        ], [
            'asrama_id.required' => 'Asrama binaan wajib dipilih.',
            'asrama_id.exists' => 'Asrama yang dipilih tidak valid atau belum terdaftar.',
            'tanggal_masuk.required' => 'Tanggal masuk asrama wajib diisi.',
            'tanggal_masuk.date' => 'Format tanggal masuk asrama tidak valid.',
        ]);

        try {
            $result = $generator->preview((int) $validated['asrama_id'], $validated['tanggal_masuk']);

            return response()->json([
                'success' => true,
                'nomor_induk' => $result['nomor_induk'],
                'nomor_urut' => $result['nomor_urut'],
                'tahun' => $result['tahun'],
                'yy' => $result['yy'],
                'kode_asrama_digits' => $result['kode_asrama_digits'],
                'message' => 'Nomor induk berhasil dipratinjau.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Menyimpan data anak asuh baru ke database dengan penomoran induk otomatis dan transaction locking.
     */
    public function store(StoreAnakAsuhRequest $request, NomorIndukGenerator $generator): RedirectResponse
    {
        $this->authorize('create', AnakAsuh::class);

        $validated = $request->validated();
        $validated['status_aktif'] = $request->boolean('status_aktif', true);

        $anakAsuh = DB::transaction(function () use ($validated, $generator) {
            $nomorIndukData = $generator->generateForCreation(
                (int) $validated['asrama_id'],
                $validated['tanggal_masuk']
            );

            $validated['nomor_induk'] = $nomorIndukData['nomor_induk'];
            if (Schema::hasColumn('anak_asuh', 'nomor_urut')) {
                $validated['nomor_urut'] = $nomorIndukData['nomor_urut'];
            }

            return AnakAsuh::create($validated);
        });

        return redirect()
            ->route('anak-asuh.index')
            ->with('success', "Data anak asuh '{$anakAsuh->nama_lengkap}' ({$anakAsuh->nomor_induk}) berhasil ditambahkan.");
    }

    /**
     * Menampilkan formulir edit data anak asuh.
     */
    public function edit(AnakAsuh $anakAsuh): View
    {
        $this->authorize('update', $anakAsuh);

        $asramas = Asrama::where('status_aktif', true)
            ->orWhere('id', $anakAsuh->asrama_id)
            ->orderBy('nama_asrama')
            ->get();

        return view('anak-asuh.edit', compact('anakAsuh', 'asramas'));
    }

    /**
     * Memperbarui data anak asuh di database.
     * Nomor induk tidak diubah secara otomatis pada mode edit.
     */
    public function update(UpdateAnakAsuhRequest $request, AnakAsuh $anakAsuh): RedirectResponse
    {
        $this->authorize('update', $anakAsuh);

        $validated = $request->validated();
        $validated['status_aktif'] = $request->boolean('status_aktif', false);
        // Pastikan nomor_induk dan nomor_urut asli tidak pernah berubah saat edit
        unset($validated['nomor_induk'], $validated['nomor_urut']);

        $anakAsuh->update($validated);

        return redirect()
            ->route('anak-asuh.index')
            ->with('success', "Perubahan data anak asuh '{$anakAsuh->nama_lengkap}' berhasil disimpan.");
    }

    /**
     * Menghapus data anak asuh dari database.
     */
    public function destroy(AnakAsuh $anakAsuh): RedirectResponse
    {
        $this->authorize('delete', $anakAsuh);

        $nama = $anakAsuh->nama_lengkap;
        $nomor = $anakAsuh->nomor_induk;

        $anakAsuh->delete();

        return redirect()
            ->route('anak-asuh.index')
            ->with('success', "Data anak asuh '{$nama}' ({$nomor}) berhasil dihapus.");
    }

    /**
     * Mengaktifkan atau menonaktifkan status anak asuh via metode PATCH.
     */
    public function toggleStatus(AnakAsuh $anakAsuh): RedirectResponse
    {
        $this->authorize('update', $anakAsuh);

        $statusBaru = ! $anakAsuh->status_aktif;
        $anakAsuh->update(['status_aktif' => $statusBaru]);

        $statusTeks = $statusBaru ? 'diaktifkan kembali' : 'dinonaktifkan';

        return redirect()
            ->back()
            ->with('success', "Status anak asuh '{$anakAsuh->nama_lengkap}' berhasil {$statusTeks}.");
    }
}
