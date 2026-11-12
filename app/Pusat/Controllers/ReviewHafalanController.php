<?php

// BAGIAN: PENGURUS PUSAT - Review & Persetujuan Hafalan Al-Qur'an & Hadis

namespace App\Pusat\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asrama;
use App\Models\EvaluasiDiniyah;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewHafalanController extends Controller
{
    /**
     * Pastikan hanya admin_pusat yang dapat menjalankan aksi mutasi (approve/revisi).
     * Middleware role:pusat membolehkan admin_pusat DAN pimpinan_pusat — kita perlu batasi lebih.
     */
    private function requireAdminPusat(): void
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $user->isAdminPusat()) {
            abort(403, 'Hanya Admin Pendik yang dapat menyetujui atau merevisi hafalan.');
        }
    }

    /**
     * Daftar setoran Hafalan Al-Qur'an yang masih pending.
     */
    public function indexQuran(Request $request): View
    {
        $query = EvaluasiDiniyah::query()
            ->where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_QURAN)
            ->where('status', EvaluasiDiniyah::STATUS_PENDING)
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

        if ($request->filled('asrama_id')) {
            $query->where('asrama_id', $request->input('asrama_id'));
        }

        $hafalans = $query->oldest('diajukan_pada')->paginate(15)->withQueryString();

        $asramaOptions = Asrama::orderBy('nama_asrama')->get();

        $totalPending = EvaluasiDiniyah::query()
            ->where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_QURAN)
            ->where('status', EvaluasiDiniyah::STATUS_PENDING)
            ->count();

        return view('pusat.views.review-hafalan.quran-index', compact('hafalans', 'asramaOptions', 'totalPending'));
    }

    /**
     * Daftar setoran Hafalan Hadis yang masih pending.
     */
    public function indexHadis(Request $request): View
    {
        $query = EvaluasiDiniyah::query()
            ->where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_HADIS)
            ->where('status', EvaluasiDiniyah::STATUS_PENDING)
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

        if ($request->filled('asrama_id')) {
            $query->where('asrama_id', $request->input('asrama_id'));
        }

        $hafalans = $query->oldest('diajukan_pada')->paginate(15)->withQueryString();

        $asramaOptions = Asrama::orderBy('nama_asrama')->get();

        $totalPending = EvaluasiDiniyah::query()
            ->where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_HADIS)
            ->where('status', EvaluasiDiniyah::STATUS_PENDING)
            ->count();

        return view('pusat.views.review-hafalan.hadis-index', compact('hafalans', 'asramaOptions', 'totalPending'));
    }

    /**
     * Detail setoran hafalan (Al-Qur'an atau Hadis) untuk ditinjau Admin Pendik.
     */
    public function show(int $id): View
    {
        $evaluasi = EvaluasiDiniyah::query()
            ->whereIn('jenis_evaluasi', [EvaluasiDiniyah::KATEGORI_QURAN, EvaluasiDiniyah::KATEGORI_HADIS])
            ->with(['anakAsuh', 'asrama', 'pengaju', 'penilai'])
            ->findOrFail($id);

        return view('pusat.views.review-hafalan.show', compact('evaluasi'));
    }

    /**
     * Setujui setoran hafalan tanpa revisi predikat.
     * Predikat akhir = predikat awal dari Staff Cabang.
     * Hanya admin_pusat yang bisa melakukan ini.
     */
    public function approve(Request $request, int $id): RedirectResponse
    {
        $this->requireAdminPusat();

        $request->validate([
            'catatan_evaluasi_pusat' => ['nullable', 'string', 'max:1000'],
        ]);

        /** @var User $reviewer */
        $reviewer = Auth::user();

        DB::transaction(function () use ($id, $request, $reviewer): void {
            // lockForUpdate mencegah approval ganda secara atomik
            $evaluasi = EvaluasiDiniyah::query()
                ->whereIn('jenis_evaluasi', [EvaluasiDiniyah::KATEGORI_QURAN, EvaluasiDiniyah::KATEGORI_HADIS])
                ->lockForUpdate()
                ->findOrFail($id);

            // Cegah approval ganda: hanya proses jika masih pending
            if ($evaluasi->status !== EvaluasiDiniyah::STATUS_PENDING) {
                return;
            }

            $evaluasi->update([
                'status' => EvaluasiDiniyah::STATUS_DISETUJUI,
                // predikat_akhir = predikat awal (tidak direvisi)
                'predikat_akhir' => $evaluasi->predikat,
                'catatan_evaluasi_pusat' => $request->input('catatan_evaluasi_pusat'),
                'dinilai_oleh' => $reviewer->id,
                'dinilai_pada' => now(),
            ]);
        });

        return redirect()->back()
            ->with('success', 'Setoran hafalan berhasil disetujui.');
    }

    /**
     * Setujui setoran hafalan dengan revisi predikat akhir.
     * Predikat awal (dari Staff Cabang) TIDAK diubah.
     * Hanya admin_pusat yang bisa melakukan ini.
     */
    public function approveWithRevisi(Request $request, int $id): RedirectResponse
    {
        $this->requireAdminPusat();

        $request->validate([
            'predikat_akhir' => ['required', 'string', 'in:A,B,C'],
            'catatan_evaluasi_pusat' => ['nullable', 'string', 'max:1000'],
        ], [
            'predikat_akhir.required' => 'Predikat akhir revisi wajib dipilih.',
            'predikat_akhir.in' => 'Predikat akhir hanya boleh A, B, atau C.',
        ]);

        /** @var User $reviewer */
        $reviewer = Auth::user();

        DB::transaction(function () use ($id, $request, $reviewer): void {
            // lockForUpdate mencegah approval ganda secara atomik
            $evaluasi = EvaluasiDiniyah::query()
                ->whereIn('jenis_evaluasi', [EvaluasiDiniyah::KATEGORI_QURAN, EvaluasiDiniyah::KATEGORI_HADIS])
                ->lockForUpdate()
                ->findOrFail($id);

            // Cegah approval ganda: hanya proses jika masih pending
            if ($evaluasi->status !== EvaluasiDiniyah::STATUS_PENDING) {
                return;
            }

            $evaluasi->update([
                'status' => EvaluasiDiniyah::STATUS_DISETUJUI,
                // predikat tetap tidak diubah (predikat awal dari Staff Cabang dipertahankan)
                'predikat_akhir' => $request->input('predikat_akhir'),
                'catatan_evaluasi_pusat' => $request->input('catatan_evaluasi_pusat'),
                'dinilai_oleh' => $reviewer->id,
                'dinilai_pada' => now(),
            ]);
        });

        return redirect()->back()
            ->with('success', 'Setoran hafalan disetujui dengan revisi predikat akhir.');
    }
}
