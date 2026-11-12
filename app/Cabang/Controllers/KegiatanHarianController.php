<?php

// BAGIAN: PENGURUS CABANG - Pengelolaan Kegiatan Harian Cabang

namespace App\Cabang\Controllers;

use App\Cabang\Services\CabangDataService;
use App\Http\Controllers\Controller;
use App\Models\KegiatanHarian;
use App\Shared\Auth\CabangSession;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KegiatanHarianController extends Controller
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

        $query = KegiatanHarian::query()
            ->where('asrama_id', $cabangId)
            ->with(['asrama', 'penginput', 'dokumentasi']);

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_kegiatan', $request->date('tanggal'));
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        $kegiatans = $query->orderByDesc('tanggal_kegiatan')->latest('id')->paginate(10)->withQueryString();
        $cabang = CabangSession::getCabang();

        return view('cabang.views.kegiatan-harian.index', compact('kegiatans', 'cabang'));
    }

    public function create(): View
    {
        $cabangId = CabangSession::getId();
        if (! $cabangId) {
            abort(403, 'Akun cabang belum terhubung dengan asrama.');
        }

        $cabang = CabangSession::getCabang();

        return view('cabang.views.kegiatan-harian.create', compact('cabang'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cabangId = CabangSession::getId();
        if (! $cabangId) {
            abort(403, 'Akun cabang belum terhubung dengan asrama.');
        }

        $validated = $request->validate([
            'tanggal_kegiatan' => ['required', 'date'],
            'kategori' => ['required', 'in:belajar,muhadhoroh,olahraga,acara_donatur,bersih_bersih'],
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'deskripsi' => ['nullable', 'string'],
            'jumlah_peserta' => ['required', 'integer', 'min:0'],
            'catatan_tambahan' => ['nullable', 'string'],
            'foto_dokumentasi' => ['nullable', 'array', 'max:5'],
            'foto_dokumentasi.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'tanggal_kegiatan.required' => 'Tanggal kegiatan wajib diisi.',
            'kategori.required' => 'Kategori kegiatan wajib dipilih.',
            'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
            'jam_mulai.required' => 'Jam mulai kegiatan wajib diisi.',
            'jam_selesai.required' => 'Jam selesai kegiatan wajib diisi.',
            'jam_selesai.after' => 'Jam selesai tidak boleh lebih awal dari jam mulai.',
            'jumlah_peserta.required' => 'Jumlah peserta wajib diisi angka.',
            'foto_dokumentasi.array' => 'Format berkas dokumentasi tidak valid.',
            'foto_dokumentasi.max' => 'Maksimal 5 foto dokumentasi yang dapat diunggah per kegiatan.',
            'foto_dokumentasi.*.image' => 'Setiap berkas dokumentasi harus berupa gambar.',
            'foto_dokumentasi.*.mimes' => 'Format file foto harus berupa JPG, JPEG, PNG, atau WebP.',
            'foto_dokumentasi.*.max' => 'Ukuran setiap foto dokumentasi maksimal 2 MB.',
        ]);

        $files = $request->hasFile('foto_dokumentasi') ? (array) $request->file('foto_dokumentasi') : [];

        // Simpan data dengan cabang_id otomatis dari session server secara transaksional
        $this->service->storeKegiatan($validated, $cabangId, (int) Auth::id(), $files);

        return redirect()->route('cabang.kegiatan-harian.index')
            ->with('success', 'Kegiatan harian berhasil disimpan.');
    }

    public function show(int $id): View
    {
        $cabangId = CabangSession::getId();
        $kegiatan = KegiatanHarian::with(['asrama', 'penginput', 'dokumentasi'])->findOrFail($id);

        // Validasi server-side: Cabang tidak boleh melihat kegiatan cabang lain
        CabangSession::validateAccess($kegiatan->asrama_id);

        return view('cabang.views.kegiatan-harian.show', compact('kegiatan'));
    }
}
