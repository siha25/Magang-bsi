<?php

namespace App\Http\Controllers;

use App\Models\AnakAsuh;
use App\Models\Asrama;
use App\Models\EvaluasiDiniyah;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluasiDiniyahController extends Controller
{
    /**
     * Menampilkan daftar riwayat evaluasi diniyah santri dengan filter dan metrik.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = EvaluasiDiniyah::query()
            ->byRole()
            ->with(['anakAsuh', 'asrama', 'pengaju']);

        // Filter Asrama (untuk Admin Pusat & Pimpinan)
        if ($request->filled('asrama_id')) {
            $query->where('asrama_id', $request->input('asrama_id'));
        }

        // Filter Jenis Evaluasi / Kategori
        if ($request->filled('jenis_evaluasi')) {
            $query->where('jenis_evaluasi', $request->input('jenis_evaluasi'));
        }

        // Filter Predikat
        if ($request->filled('predikat')) {
            $query->where('predikat', $request->input('predikat'));
        }

        // Filter Bulan
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->input('bulan'));
        }

        // Filter Tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->input('tahun'));
        }

        // Pencarian Nama Santri, Nomor Induk, atau Materi
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

        // Menghitung statistik metrik
        $statsQuery = clone $query;
        $totalEvaluasi = $statsQuery->count();
        $rataRataNilai = $totalEvaluasi > 0 ? round((float) $statsQuery->avg('nilai'), 1) : 0;
        $mumtazCount = (clone $query)->where('nilai', '>=', 85)->count();
        $totalSantri = (clone $query)->distinct('anak_asuh_id')->count('anak_asuh_id');

        $stats = [
            'total_evaluasi' => $totalEvaluasi,
            'rata_rata' => $rataRataNilai,
            'mumtaz_count' => $mumtazCount,
            'total_santri' => $totalSantri,
        ];

        $evaluasis = $query->latest('tanggal_evaluasi')->latest('id')->paginate(12)->withQueryString();

        $asramaOptions = Asrama::query()
            ->when($user?->isAdminCabang(), fn ($q) => $q->where('id', $user->asrama_id ?? 0))
            ->orderBy('nama_asrama')
            ->get();

        $kategoriList = [
            EvaluasiDiniyah::KATEGORI_QURAN,
            EvaluasiDiniyah::KATEGORI_HADIS,
            EvaluasiDiniyah::KATEGORI_PENGETAHUAN,
            EvaluasiDiniyah::KATEGORI_FIQIH,
            EvaluasiDiniyah::KATEGORI_AQIDAH,
            EvaluasiDiniyah::KATEGORI_TAJWID,
        ];

        return view('evaluasi-diniyah.index', compact(
            'evaluasis',
            'asramaOptions',
            'stats',
            'kategoriList'
        ));
    }

    /**
     * Menampilkan formulir input nilai evaluasi diniyah santri.
     */
    public function create(Request $request): View
    {
        $user = Auth::user();

        if ($user?->isAdminCabang()) {
            $asramas = Asrama::where('id', $user->asrama_id)->get();
            $defaultAsramaId = $user->asrama_id;
            $anakAsuhList = AnakAsuh::where('asrama_id', $user->asrama_id)
                ->aktif()
                ->orderBy('nama_lengkap')
                ->get();
        } else {
            $asramas = Asrama::where('status_aktif', true)->orderBy('nama_asrama')->get();
            $defaultAsramaId = $request->input('asrama_id', $asramas->first()?->id);
            $anakAsuhList = AnakAsuh::query()
                ->when($defaultAsramaId, fn ($q) => $q->where('asrama_id', $defaultAsramaId))
                ->aktif()
                ->orderBy('nama_lengkap')
                ->get();
        }

        $kategoriList = [
            EvaluasiDiniyah::KATEGORI_QURAN,
            EvaluasiDiniyah::KATEGORI_HADIS,
            EvaluasiDiniyah::KATEGORI_PENGETAHUAN,
            EvaluasiDiniyah::KATEGORI_FIQIH,
            EvaluasiDiniyah::KATEGORI_AQIDAH,
            EvaluasiDiniyah::KATEGORI_TAJWID,
        ];

        $selectedJenis = $request->query('jenis_evaluasi', '');
        $selectedAnakId = $request->query('anak_asuh_id');

        return view('evaluasi-diniyah.create', compact(
            'asramas',
            'anakAsuhList',
            'defaultAsramaId',
            'kategoriList',
            'selectedJenis',
            'selectedAnakId'
        ));
    }

    /**
     * Menyimpan nilai evaluasi diniyah baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'asrama_id' => ['required', 'integer', 'exists:asrama,id'],
            'anak_asuh_id' => ['required', 'integer', 'exists:anak_asuh,id'],
            'jenis_evaluasi' => ['required', 'string', 'max:100'],
            'materi' => ['required', 'string', 'max:255'],
            'tanggal_evaluasi' => ['required', 'date'],
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'predikat' => ['nullable', 'string', 'max:50'],
            'catatan_pengaju' => ['nullable', 'string', 'max:1000'],
        ], [
            'asrama_id.required' => 'Asrama cabang wajib dipilih.',
            'anak_asuh_id.required' => 'Santri / anak asuh wajib dipilih.',
            'jenis_evaluasi.required' => 'Jenis evaluasi diniyah wajib dipilih.',
            'materi.required' => 'Materi / judul ulangan wajib diisi.',
            'tanggal_evaluasi.required' => 'Tanggal evaluasi wajib diisi.',
            'nilai.required' => 'Nilai ulangan wajib diisi angka antara 0 - 100.',
            'nilai.min' => 'Nilai minimal adalah 0.',
            'nilai.max' => 'Nilai maksimal adalah 100.',
        ]);

        // Verifikasi kepemilikan asrama jika Admin Cabang
        if ($user?->isAdminCabang() && (int) $validated['asrama_id'] !== (int) $user->asrama_id) {
            abort(403, 'Anda hanya dapat menginput nilai evaluasi untuk cabang asrama Anda sendiri.');
        }

        // Verifikasi anak asuh bernaung di asrama yang dipilih
        $anakAsuh = AnakAsuh::findOrFail($validated['anak_asuh_id']);
        if ((int) $anakAsuh->asrama_id !== (int) $validated['asrama_id']) {
            return back()->withInput()->withErrors([
                'anak_asuh_id' => 'Santri yang dipilih tidak terdaftar di asrama tersebut.',
            ]);
        }

        $validated['diajukan_oleh'] = Auth::id();
        $validated['diajukan_pada'] = now();
        $validated['status'] = EvaluasiDiniyah::STATUS_DISETUJUI;

        EvaluasiDiniyah::create($validated);

        return redirect()->route('evaluasi-diniyah.index')->with(
            'success',
            'Nilai evaluasi diniyah untuk '.$anakAsuh->nama_lengkap.' berhasil disimpan.'
        );
    }

    /**
     * Menampilkan detail nilai evaluasi santri.
     */
    public function show(EvaluasiDiniyah $evaluasiDiniyah): View
    {
        $user = Auth::user();

        if ($user?->isAdminCabang() && (int) $user->asrama_id !== (int) $evaluasiDiniyah->asrama_id) {
            abort(403, 'Anda tidak berhak melihat data evaluasi asrama lain.');
        }

        $evaluasiDiniyah->load(['anakAsuh.asrama', 'asrama', 'pengaju', 'penilai']);

        return view('evaluasi-diniyah.show', compact('evaluasiDiniyah'));
    }

    /**
     * Menampilkan formulir edit nilai evaluasi.
     */
    public function edit(EvaluasiDiniyah $evaluasiDiniyah): View
    {
        $user = Auth::user();

        if ($user?->isAdminCabang() && (int) $user->asrama_id !== (int) $evaluasiDiniyah->asrama_id) {
            abort(403, 'Anda tidak dapat mengedit nilai evaluasi asrama lain.');
        }

        if ($user?->isAdminCabang()) {
            $asramas = Asrama::where('id', $user->asrama_id)->get();
            $anakAsuhList = AnakAsuh::where('asrama_id', $user->asrama_id)
                ->aktif()
                ->orderBy('nama_lengkap')
                ->get();
        } else {
            $asramas = Asrama::where('status_aktif', true)->orderBy('nama_asrama')->get();
            $anakAsuhList = AnakAsuh::where('asrama_id', $evaluasiDiniyah->asrama_id)
                ->aktif()
                ->orderBy('nama_lengkap')
                ->get();
        }

        $kategoriList = [
            EvaluasiDiniyah::KATEGORI_QURAN,
            EvaluasiDiniyah::KATEGORI_HADIS,
            EvaluasiDiniyah::KATEGORI_PENGETAHUAN,
            EvaluasiDiniyah::KATEGORI_FIQIH,
            EvaluasiDiniyah::KATEGORI_AQIDAH,
            EvaluasiDiniyah::KATEGORI_TAJWID,
        ];

        return view('evaluasi-diniyah.edit', compact(
            'evaluasiDiniyah',
            'asramas',
            'anakAsuhList',
            'kategoriList'
        ));
    }

    /**
     * Menyimpan pembaruan nilai evaluasi.
     */
    public function update(Request $request, EvaluasiDiniyah $evaluasiDiniyah): RedirectResponse
    {
        $user = Auth::user();

        if ($user?->isAdminCabang() && (int) $user->asrama_id !== (int) $evaluasiDiniyah->asrama_id) {
            abort(403, 'Anda tidak berhak mengubah evaluasi asrama lain.');
        }

        $validated = $request->validate([
            'asrama_id' => ['required', 'integer', 'exists:asrama,id'],
            'anak_asuh_id' => ['required', 'integer', 'exists:anak_asuh,id'],
            'jenis_evaluasi' => ['required', 'string', 'max:100'],
            'materi' => ['required', 'string', 'max:255'],
            'tanggal_evaluasi' => ['required', 'date'],
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'predikat' => ['nullable', 'string', 'max:50'],
            'catatan_pengaju' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($user?->isAdminCabang() && (int) $validated['asrama_id'] !== (int) $user->asrama_id) {
            abort(403, 'Anda tidak dapat memindahkan evaluasi ke asrama lain.');
        }

        $anakAsuh = AnakAsuh::findOrFail($validated['anak_asuh_id']);
        if ((int) $anakAsuh->asrama_id !== (int) $validated['asrama_id']) {
            return back()->withInput()->withErrors([
                'anak_asuh_id' => 'Santri yang dipilih tidak terdaftar di asrama tersebut.',
            ]);
        }

        $evaluasiDiniyah->update($validated);

        return redirect()->route('evaluasi-diniyah.index')->with(
            'success',
            'Nilai evaluasi diniyah berhasil diperbarui.'
        );
    }

    /**
     * Menghapus catatan evaluasi santri.
     */
    public function destroy(EvaluasiDiniyah $evaluasiDiniyah): RedirectResponse
    {
        $user = Auth::user();

        if ($user?->isAdminCabang() && (int) $user->asrama_id !== (int) $evaluasiDiniyah->asrama_id) {
            abort(403, 'Anda tidak berhak menghapus evaluasi asrama lain.');
        }

        $evaluasiDiniyah->delete();

        return redirect()->route('evaluasi-diniyah.index')->with(
            'success',
            'Catatan evaluasi diniyah berhasil dihapus.'
        );
    }

    /**
     * Endpoint API untuk mengambil daftar santri berdasarkan asrama (digunakan oleh form dinamis).
     */
    public function getSantriByAsrama(int $asramaId): JsonResponse
    {
        $user = Auth::user();

        if ($user?->isAdminCabang() && (int) $user->asrama_id !== $asramaId) {
            return response()->json([], 403);
        }

        $santri = AnakAsuh::where('asrama_id', $asramaId)
            ->aktif()
            ->orderBy('nama_lengkap')
            ->get(['id', 'nomor_induk', 'nama_lengkap', 'jenjang_pendidikan', 'kelas']);

        return response()->json($santri);
    }
}
