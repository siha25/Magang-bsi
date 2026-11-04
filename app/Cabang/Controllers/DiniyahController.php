<?php

// BAGIAN: PENGURUS CABANG - Input & Pengelolaan Pengetahuan Diniyah Cabang

namespace App\Cabang\Controllers;

use App\Cabang\Services\CabangDataService;
use App\Http\Controllers\Controller;
use App\Models\EvaluasiDiniyah;
use App\Shared\Auth\CabangSession;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiniyahController extends Controller
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
            ->where('jenis_evaluasi', '!=', "Hafalan Al-Qur'an")
            ->with(['anakAsuh', 'asrama', 'pengaju']);

        if ($request->filled('jenis_evaluasi')) {
            $query->where('jenis_evaluasi', $request->input('jenis_evaluasi'));
        }

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

        $diniyahs = $query->latest('tanggal_evaluasi')->latest('id')->paginate(10)->withQueryString();
        $cabang = CabangSession::getCabang();

        $kategoriList = [
            'Hafalan Hadis',
            'Pengetahuan Diniyah',
            'Fiqih & Ibadah',
            'Aqidah & Akhlak',
            'Tajwid & Tahsin',
        ];

        return view('cabang.views.diniyah.index', compact('diniyahs', 'cabang', 'kategoriList'));
    }

    public function create(Request $request): View
    {
        $cabangId = CabangSession::getId();
        if (! $cabangId) {
            abort(403, 'Akun cabang belum terhubung dengan asrama.');
        }

        $cabang = CabangSession::getCabang();
        $santriList = $this->service->getSantriList($cabangId);
        $kategoriList = [
            'Hafalan Hadis',
            'Pengetahuan Diniyah',
            'Fiqih & Ibadah',
            'Aqidah & Akhlak',
            'Tajwid & Tahsin',
        ];
        $selectedSantriId = $request->query('anak_asuh_id');

        return view('cabang.views.diniyah.create', compact('cabang', 'santriList', 'kategoriList', 'selectedSantriId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cabangId = CabangSession::getId();
        if (! $cabangId) {
            abort(403, 'Akun cabang belum terhubung dengan asrama.');
        }

        $validated = $request->validate([
            'anak_asuh_id' => ['required', 'integer', 'exists:anak_asuh,id'],
            'jenis_evaluasi' => ['required', 'string', 'max:100'],
            'materi' => ['required', 'string', 'max:255'],
            'tanggal_evaluasi' => ['required', 'date'],
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'catatan_pengaju' => ['nullable', 'string', 'max:1000'],
        ], [
            'anak_asuh_id.required' => 'Santri wajib dipilih.',
            'jenis_evaluasi.required' => 'Kategori pengetahuan diniyah wajib dipilih.',
            'materi.required' => 'Materi / bab ulangan wajib diisi.',
            'tanggal_evaluasi.required' => 'Tanggal evaluasi wajib diisi.',
            'nilai.required' => 'Nilai ulangan wajib diisi angka 0 - 100.',
        ]);

        // Simpan evaluasi pengetahuan diniyah dengan cabang_id otomatis dari session server
        $this->service->storeEvaluasi($validated, $cabangId, (int) Auth::id());

        return redirect()->route('cabang.diniyah.index')
            ->with('success', 'Nilai pengetahuan diniyah santri berhasil disimpan.');
    }

    public function show(int $id): View
    {
        $evaluasi = EvaluasiDiniyah::with(['anakAsuh', 'asrama', 'pengaju'])->findOrFail($id);

        CabangSession::validateAccess($evaluasi->asrama_id);

        return view('cabang.views.diniyah.show', compact('evaluasi'));
    }

    public function edit(int $id): View
    {
        $cabangId = CabangSession::getId();
        $evaluasi = EvaluasiDiniyah::with('anakAsuh')->findOrFail($id);

        CabangSession::validateAccess($evaluasi->asrama_id);

        $cabang = CabangSession::getCabang();
        $santriList = $this->service->getSantriList($cabangId);
        $kategoriList = [
            'Hafalan Hadis',
            'Pengetahuan Diniyah',
            'Fiqih & Ibadah',
            'Aqidah & Akhlak',
            'Tajwid & Tahsin',
        ];

        return view('cabang.views.diniyah.edit', compact('evaluasi', 'cabang', 'santriList', 'kategoriList'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $cabangId = CabangSession::getId();
        $evaluasi = EvaluasiDiniyah::findOrFail($id);

        CabangSession::validateAccess($evaluasi->asrama_id);

        $validated = $request->validate([
            'anak_asuh_id' => ['required', 'integer', 'exists:anak_asuh,id'],
            'jenis_evaluasi' => ['required', 'string', 'max:100'],
            'materi' => ['required', 'string', 'max:255'],
            'tanggal_evaluasi' => ['required', 'date'],
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'catatan_pengaju' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['predikat'] = EvaluasiDiniyah::hitungPredikat((float) $validated['nilai']);

        $this->service->updateEvaluasi($evaluasi, $validated, $cabangId);

        return redirect()->route('cabang.diniyah.index')
            ->with('success', 'Nilai evaluasi diniyah santri berhasil diperbarui.');
    }
}
