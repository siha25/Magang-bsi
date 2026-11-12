<?php

// BAGIAN: PENGURUS CABANG - Input & Riwayat Hafalan Hadis

namespace App\Cabang\Controllers;

use App\Cabang\Services\CabangDataService;
use App\Http\Controllers\Controller;
use App\Models\AnakAsuh;
use App\Models\EvaluasiDiniyah;
use App\Shared\Auth\CabangSession;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HafalanHadistController extends Controller
{
    public function __construct(
        protected CabangDataService $service
    ) {}

    public function index(Request $request): View
    {
        $cabangId = CabangSession::getId();
        if (! $cabangId) {
            abort(403, 'Akun cabang belum terhubung dengan asrama.');
        }

        $query = EvaluasiDiniyah::query()
            ->where('asrama_id', $cabangId)
            ->where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_HADIS)
            ->with(['anakAsuh', 'asrama', 'pengaju']);

        if ($request->filled('search')) {
            $term = trim($request->input('search'));
            $query->where(function ($q) use ($term): void {
                $q->where('materi', 'like', "%{$term}%")
                    ->orWhereHas('anakAsuh', function ($sub) use ($term): void {
                        $sub->where('nama_lengkap', 'like', "%{$term}%")
                            ->orWhere('nomor_induk', 'like', "%{$term}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->input('bulan'));
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->input('tahun'));
        }

        $hafalans = $query->latest('tanggal_evaluasi')->latest('id')->paginate(10)->withQueryString();
        $cabang = CabangSession::getCabang();

        $totalQuery = EvaluasiDiniyah::query()
            ->where('asrama_id', $cabangId)
            ->where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_HADIS);

        $stats = [
            'total' => (clone $totalQuery)->count(),
            'pending' => (clone $totalQuery)->where('status', EvaluasiDiniyah::STATUS_PENDING)->count(),
            'disetujui' => (clone $totalQuery)->where('status', EvaluasiDiniyah::STATUS_DISETUJUI)->count(),
        ];

        return view('cabang.views.hafalan-hadist.index', compact('hafalans', 'cabang', 'stats'));
    }

    public function create(Request $request): View
    {
        $cabangId = CabangSession::getId();
        if (! $cabangId) {
            abort(403, 'Akun cabang belum terhubung dengan asrama.');
        }

        $cabang = CabangSession::getCabang();
        $santriList = $this->service->getSantriList($cabangId);
        $selectedSantriId = $request->query('anak_asuh_id');

        return view('cabang.views.hafalan-hadist.create', compact('cabang', 'santriList', 'selectedSantriId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cabangId = CabangSession::getId();
        if (! $cabangId) {
            abort(403, 'Akun cabang belum terhubung dengan asrama.');
        }

        $validated = $request->validate([
            'anak_asuh_id' => ['required', 'integer', 'exists:anak_asuh,id'],
            'materi' => ['required', 'string', 'max:255'],
            'tanggal_evaluasi' => ['required', 'date'],
            'predikat' => ['required', 'string', 'in:A,B,C'],
            'catatan_pengaju' => ['nullable', 'string', 'max:1000'],
        ], [
            'anak_asuh_id.required' => 'Santri wajib dipilih.',
            'materi.required' => 'Hadis yang disetorkan wajib diisi.',
            'tanggal_evaluasi.required' => 'Tanggal setoran wajib diisi.',
            'predikat.required' => 'Predikat hafalan hadis wajib dipilih (A, B, atau C).',
            'predikat.in' => 'Predikat hafalan hadis hanya boleh A, B, atau C.',
        ]);

        // Pastikan santri milik cabang ini
        $santri = AnakAsuh::query()
            ->where('id', $validated['anak_asuh_id'])
            ->where('asrama_id', $cabangId)
            ->firstOrFail();

        $validated['jenis_evaluasi'] = EvaluasiDiniyah::KATEGORI_HADIS;
        $validated['status'] = EvaluasiDiniyah::STATUS_PENDING;
        $validated['asrama_id'] = $cabangId;
        $validated['diajukan_oleh'] = (int) Auth::id();
        $validated['diajukan_pada'] = now();
        // nilai tidak diperlukan untuk hafalan hadis; predikat dari input manual
        $validated['nilai'] = null;

        EvaluasiDiniyah::create($validated);

        return redirect()->route('cabang.hafalan-hadist.index')
            ->with('success', "Setoran hafalan Hadis {$santri->nama_lengkap} berhasil disimpan. Menunggu persetujuan Admin Pendik.");
    }

    public function show(int $id): View
    {
        $evaluasi = EvaluasiDiniyah::with(['anakAsuh', 'asrama', 'pengaju', 'penilai'])->findOrFail($id);

        // Validasi hak akses cabang
        CabangSession::validateAccess($evaluasi->asrama_id);

        // Pastikan jenis evaluasi benar
        if ($evaluasi->jenis_evaluasi !== EvaluasiDiniyah::KATEGORI_HADIS) {
            abort(404, 'Data hafalan hadis tidak ditemukan.');
        }

        return view('cabang.views.hafalan-hadist.show', compact('evaluasi'));
    }

    public function edit(int $id): View
    {
        $cabangId = CabangSession::getId();
        $evaluasi = EvaluasiDiniyah::with('anakAsuh')->findOrFail($id);

        CabangSession::validateAccess($evaluasi->asrama_id);

        // Pastikan jenis evaluasi benar
        if ($evaluasi->jenis_evaluasi !== EvaluasiDiniyah::KATEGORI_HADIS) {
            abort(404, 'Data hafalan hadis tidak ditemukan.');
        }

        // Hanya boleh edit jika masih pending
        if (! $evaluasi->isBisaDieditCabang()) {
            return redirect()->route('cabang.hafalan-hadist.show', $id)
                ->with('error', 'Data hafalan yang sudah disetujui tidak dapat diubah.');
        }

        $cabang = CabangSession::getCabang();
        $santriList = $this->service->getSantriList($cabangId);

        return view('cabang.views.hafalan-hadist.edit', compact('evaluasi', 'cabang', 'santriList'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $cabangId = CabangSession::getId();
        $evaluasi = EvaluasiDiniyah::findOrFail($id);

        CabangSession::validateAccess($evaluasi->asrama_id);

        // Pastikan jenis evaluasi benar
        if ($evaluasi->jenis_evaluasi !== EvaluasiDiniyah::KATEGORI_HADIS) {
            abort(404, 'Data hafalan hadis tidak ditemukan.');
        }

        // Hanya boleh edit jika masih pending
        if (! $evaluasi->isBisaDieditCabang()) {
            return redirect()->route('cabang.hafalan-hadist.show', $id)
                ->with('error', 'Data hafalan yang sudah disetujui tidak dapat diubah.');
        }

        $validated = $request->validate([
            'anak_asuh_id' => ['required', 'integer', 'exists:anak_asuh,id'],
            'materi' => ['required', 'string', 'max:255'],
            'tanggal_evaluasi' => ['required', 'date'],
            'predikat' => ['required', 'string', 'in:A,B,C'],
            'catatan_pengaju' => ['nullable', 'string', 'max:1000'],
        ], [
            'predikat.required' => 'Predikat hafalan hadis wajib dipilih (A, B, atau C).',
            'predikat.in' => 'Predikat hafalan hadis hanya boleh A, B, atau C.',
        ]);

        // Status tetap pending setelah edit cabang
        $validated['status'] = EvaluasiDiniyah::STATUS_PENDING;
        $validated['asrama_id'] = $cabangId;

        $this->service->updateEvaluasi($evaluasi, $validated, $cabangId);

        return redirect()->route('cabang.hafalan-hadist.index')
            ->with('success', 'Data setoran hafalan hadis berhasil diperbarui.');
    }
}
