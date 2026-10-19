<?php

namespace App\Http\Controllers;

use App\Models\AnakAsuh;
use App\Models\Asrama;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama divisi pendidikan anak asuh dengan data aktual dari database.
     */
    public function index(): View
    {
        $periode = [
            'bulan' => 'Oktober',
            'tahun' => (string) date('Y'),
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
        ];

        $currentUser = auth()->user();
        $pengurus = [
            'nama' => $currentUser ? $currentUser->name : 'Pengurus',
            'jabatan' => $currentUser ? ($currentUser->isAdminPusat() ? 'Administrator Pusat' : ($currentUser->isAdminAsrama() ? 'Administrator Asrama' : 'Pimpinan / Koordinator')) : 'Pengurus',
            'yayasan' => 'Yayasan Rumah Harapan',
            'email' => $currentUser ? $currentUser->email : 'pengurus@rumahharapan.org',
            'avatar' => null,
            'status' => 'Aktif Bertugas',
        ];

        // 1. Data Aktual Asrama dari Database
        $totalAsrama = Asrama::count();
        $asramaAktif = Asrama::where('status_aktif', true)->count();

        // 2. Data Aktual Anak Asuh dari Database
        $totalAnak = AnakAsuh::count();
        $anakAktif = AnakAsuh::where('status_aktif', true)->count();

        // 3. Distribusi Jenjang Pendidikan dari Database
        $jenjangCounts = AnakAsuh::selectRaw('UPPER(jenjang_pendidikan) as jenjang, count(*) as total')
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
            // Kompatibilitas mundur jika ada referensi total_cabang
            'total_cabang' => $totalAsrama,
        ];

        // 4. Daftar Asrama Aktual beserta sebaran jenjang anak asuh
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

        // 5. Kategori Kurikulum (Belum tersedia data nyata di database)
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

        // 6. Aktivitas Terkini (Kosong informatif sampai ada sumber data nyata)
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
