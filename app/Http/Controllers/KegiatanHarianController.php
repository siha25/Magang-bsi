<?php

namespace App\Http\Controllers;

use App\Models\Asrama;
use App\Models\KegiatanHarian;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KegiatanHarianController extends Controller
{
    public function index(Request $request): View
    {
        $query = KegiatanHarian::query()
            ->byRole()
            ->with(['asrama', 'penginput']);

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_kegiatan', $request->date('tanggal'));
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        if ($request->filled('asrama_id')) {
            $query->where('asrama_id', $request->input('asrama_id'));
        }

        $kegiatans = $query->orderByDesc('tanggal_kegiatan')->paginate(10)->withQueryString();

        $asramaOptions = Asrama::query()
            ->when(
                Auth::user()?->isAdminCabang(),
                fn ($query) => $query->where('id', Auth::user()->asrama_id ?? 0)
            )
            ->orderBy('nama_asrama')
            ->get();

        return view('kegiatan-harian.index', compact('kegiatans', 'asramaOptions'));
    }

    public function show(int $kegiatanHarian): View
    {
        $kegiatan = KegiatanHarian::query()
            ->byRole()
            ->with(['asrama', 'penginput'])
            ->findOrFail($kegiatanHarian);

        return view('kegiatan-harian.show', compact('kegiatan'));
    }

    public function create(): View
    {
        $asramas = Auth::user()?->isAdminPusat()
            ? Asrama::orderBy('nama_asrama')->get()
            : collect([Auth::user()?->asrama])->filter();

        return view('kegiatan-harian.create', compact('asramas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'asrama_id' => ['required', 'integer', 'exists:asrama,id'],
            'tanggal_kegiatan' => ['required', 'date'],
            'kategori' => ['required', 'in:belajar,muhadhoroh,olahraga,acara_donatur,bersih_bersih'],
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'deskripsi' => ['nullable', 'string'],
            'jumlah_peserta' => ['required', 'integer', 'min:0'],
            'catatan_tambahan' => ['nullable', 'string'],
        ], [
            'jam_selesai.after' => 'Jam selesai tidak boleh lebih awal dari jam mulai.',
        ]);

        if (Auth::user()?->isAdminCabang() && (int) $validated['asrama_id'] !== (int) Auth::user()->asrama_id) {
            abort(403, 'Anda hanya dapat mengelola kegiatan asrama Anda sendiri.');
        }

        $validated['penginput_id'] = Auth::id();

        KegiatanHarian::create($validated);

        return redirect()->route('kegiatan-harian.index')->with('success', 'Kegiatan harian berhasil disimpan.');
    }
}
