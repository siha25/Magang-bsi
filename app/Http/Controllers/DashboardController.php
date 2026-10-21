<?php

namespace App\Http\Controllers;

use App\Models\AnakAsuh;
use App\Models\Asrama;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama divisi pendidikan anak asuh dengan data aktual dari database.
     */
    public function index(): View
    {
        $currentUser = Auth::user();

        if ($currentUser?->isAdminCabang()) {
            $asramaList = Asrama::where('id', $currentUser->asrama_id)
                ->withCount([
                    'anakAsuh',
                    'anakAsuh as sd_count' => function ($query): void {
                        $query->whereRaw('UPPER(jenjang_pendidikan) = ?', ['SD']);
                    },
                    'anakAsuh as smp_count' => function ($query): void {
                        $query->whereRaw('UPPER(jenjang_pendidikan) = ?', ['SMP']);
                    },
                    'anakAsuh as sma_count' => function ($query): void {
                        $query->whereRaw('UPPER(jenjang_pendidikan) = ?', ['SMA']);
                    },
                ])->latest()->get();

            $totalAsrama = $asramaList->count();
            $asramaAktif = $asramaList->where('status_aktif', true)->count();
            $totalAnak = AnakAsuh::where('asrama_id', $currentUser->asrama_id)->count();
            $anakAktif = AnakAsuh::where('asrama_id', $currentUser->asrama_id)->where('status_aktif', true)->count();
        } else {
            $asramaList = Asrama::withCount([
                'anakAsuh',
                'anakAsuh as sd_count' => function ($query): void {
                    $query->whereRaw('UPPER(jenjang_pendidikan) = ?', ['SD']);
                },
                'anakAsuh as smp_count' => function ($query): void {
                    $query->whereRaw('UPPER(jenjang_pendidikan) = ?', ['SMP']);
                },
                'anakAsuh as sma_count' => function ($query): void {
                    $query->whereRaw('UPPER(jenjang_pendidikan) = ?', ['SMA']);
                },
            ])->latest()->get();

            $totalAsrama = Asrama::count();
            $asramaAktif = Asrama::where('status_aktif', true)->count();
            $totalAnak = AnakAsuh::count();
            $anakAktif = AnakAsuh::where('status_aktif', true)->count();
        }

        $periode = [
            'bulan' => 'Oktober',
            'tahun' => (string) date('Y'),
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
        ];

        $pengurus = [
            'nama' => $currentUser ? $currentUser->name : 'Pengurus',
            'jabatan' => $currentUser ? (
                $currentUser->isAdminPusat() ? 'Admin Pusat' : ($currentUser->isAdminCabang() ? 'Admin Cabang' : ($currentUser->isPimpinanPusat() ? 'Pimpinan Pusat' : 'Pengurus'))
            ) : 'Pengurus',
            'yayasan' => 'Yayasan Rumah Harapan',
            'email' => $currentUser ? $currentUser->email : 'pengurus@rumahharapan.org',
            'avatar' => null,
            'status' => 'Aktif Bertugas',
        ];

        $jenjangCounts = AnakAsuh::selectRaw('UPPER(jenjang_pendidikan) as jenjang, count(*) as total')
            ->when($currentUser?->isAdminCabang(), fn ($query) => $query->where('asrama_id', $currentUser->asrama_id))
            ->groupBy('jenjang')
            ->pluck('total', 'jenjang');

        $sdCount = (int) ($jenjangCounts->get('SD') ?? 0);
        $smpCount = (int) ($jenjangCounts->get('SMP') ?? 0);
        $smaCount = (int) ($jenjangCounts->get('SMA') ?? 0);

        $sdPersen = $totalAnak > 0 ? (int) round(($sdCount / $totalAnak) * 100) : 0;
        $smpPersen = $totalAnak > 0 ? (int) round(($smpCount / $totalAnak) * 100) : 0;
        $smaPersen = $totalAnak > 0 ? (int) round(($smaCount / $totalAnak) * 100) : 0;

        $stats = [
            'total_asrama' => $totalAsrama,
            'asrama_aktif' => $asramaAktif,
            'total_anak' => $totalAnak,
            'anak_aktif' => $anakAktif,
            'jenjang' => [
                'sd' => $sdCount,
                'smp' => $smpCount,
                'sma' => $smaCount,
                'sd_persen' => $sdPersen,
                'smp_persen' => $smpPersen,
                'sma_persen' => $smaPersen,
            ],
            'total_cabang' => $totalAsrama,
        ];

        $targetKategori = [
            [
                'judul' => "Hafalan Al-Qur'an",
                'subjudul' => 'Target minimal 1 juz per semester per anak',
                'status' => 'Belum tersedia',
            ],
            [
                'judul' => 'Hafalan Hadis Arbain',
                'subjudul' => 'Target 15-20 hadis per semester',
                'status' => 'Belum tersedia',
            ],
            [
                'judul' => 'Pengetahuan Diniyah (Fiqih & Aqidah)',
                'subjudul' => 'Materi standar kurikulum Rumah Harapan',
                'status' => 'Belum tersedia',
            ],
            [
                'judul' => 'Disiplin Ibadah & Mutabaah',
                'subjudul' => 'Pemantauan buku mutabaah harian',
                'status' => 'Belum tersedia',
            ],
        ];

        $aktivitasTerbaru = [];

        return view('dashboard', compact(
            'periode',
            'pengurus',
            'stats',
            'asramaList',
            'targetKategori',
            'aktivitasTerbaru'
        ));
    }
}
