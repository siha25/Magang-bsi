<?php

// BAGIAN: PENGURUS CABANG - Input & Riwayat Hafalan Al-Qur'an

namespace App\Cabang\Controllers;

use App\Cabang\Services\CabangDataService;
use App\Http\Controllers\Controller;
use App\Models\EvaluasiDiniyah;
use App\Shared\Auth\CabangSession;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HafalanQuranController extends Controller
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
            ->where('jenis_evaluasi', "Hafalan Al-Qur'an")
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

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->input('bulan'));
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->input('tahun'));
        }

        $hafalans = $query->latest('tanggal_evaluasi')->latest('id')->paginate(10)->withQueryString();
        $cabang = CabangSession::getCabang();

        $stats = [
            'total' => (clone $query)->count(),
            'rata_rata' => round((float) ((clone $query)->avg('nilai') ?? 0), 1),
            'mumtaz' => (clone $query)->where('nilai', '>=', 85)->count(),
        ];

        return view('cabang.views.hafalan-quran.index', compact('hafalans', 'cabang', 'stats'));
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

        return view('cabang.views.hafalan-quran.create', compact('cabang', 'santriList', 'selectedSantriId'));
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
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'catatan_pengaju' => ['nullable', 'string', 'max:1000'],
        ], [
            'anak_asuh_id.required' => 'Santri wajib dipilih.',
            'materi.required' => 'Surah / Juz hafalan wajib diisi.',
            'tanggal_evaluasi.required' => 'Tanggal evaluasi hafalan wajib diisi.',
            'nilai.required' => 'Nilai hafalan wajib diisi angka 0 - 100.',
        ]);

        $validated['jenis_evaluasi'] = "Hafalan Al-Qur'an";

        // Simpan evaluasi hafalan dengan cabang_id otomatis dari session server
        $this->service->storeEvaluasi($validated, $cabangId, (int) Auth::id());

        return redirect()->route('cabang.hafalan-quran.index')
            ->with('success', "Data hafalan Al-Qur'an santri berhasil disimpan.");
    }

    public function show(int $id): View
    {
        $evaluasi = EvaluasiDiniyah::with(['anakAsuh', 'asrama', 'pengaju'])->findOrFail($id);

        // Validasi hak akses cabang
        CabangSession::validateAccess($evaluasi->asrama_id);

        return view('cabang.views.hafalan-quran.show', compact('evaluasi'));
    }

    public function edit(int $id): View
    {
        $cabangId = CabangSession::getId();
        $evaluasi = EvaluasiDiniyah::with('anakAsuh')->findOrFail($id);

        CabangSession::validateAccess($evaluasi->asrama_id);

        $cabang = CabangSession::getCabang();
        $santriList = $this->service->getSantriList($cabangId);

        return view('cabang.views.hafalan-quran.edit', compact('evaluasi', 'cabang', 'santriList'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $cabangId = CabangSession::getId();
        $evaluasi = EvaluasiDiniyah::findOrFail($id);

        CabangSession::validateAccess($evaluasi->asrama_id);

        $validated = $request->validate([
            'anak_asuh_id' => ['required', 'integer', 'exists:anak_asuh,id'],
            'materi' => ['required', 'string', 'max:255'],
            'tanggal_evaluasi' => ['required', 'date'],
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'catatan_pengaju' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['predikat'] = EvaluasiDiniyah::hitungPredikat((float) $validated['nilai']);

        $this->service->updateEvaluasi($evaluasi, $validated, $cabangId);

        return redirect()->route('cabang.hafalan-quran.index')
            ->with('success', 'Data hafalan santri berhasil diperbarui.');
    }
}
