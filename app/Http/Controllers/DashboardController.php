<?php

namespace App\Http\Controllers;

use App\Models\AnakAsuh;
use App\Models\Asrama;
use App\Models\EvaluasiDiniyah;
use App\Models\KegiatanHarian;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Daftar nama bulan dalam bahasa Indonesia.
     *
     * @var array<int, string>
     */
    private const BULAN_LIST = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    /**
     * Menampilkan Dashboard Admin Pusat dengan data aktual dari database.
     * Mendukung filter: periode (bulan & tahun), jenjang pendidikan, dan cakupan asrama.
     */
    public function index(Request $request): View
    {
        $currentUser = Auth::user();

        // ─── Validasi & Ekstraksi Parameter Filter ────────────────────────────
        $filterBulanNum = (int) $request->input('bulan', (int) date('n'));
        $filterTahun = (int) $request->input('tahun', (int) date('Y'));
        $filterJenjang = $request->input('jenjang');
        $filterAsramaId = $request->input('asrama_id');

        if ($filterBulanNum < 1 || $filterBulanNum > 12) {
            $filterBulanNum = (int) date('n');
        }
        if ($filterTahun < 2020 || $filterTahun > 2100) {
            $filterTahun = (int) date('Y');
        }

        $filterBulanNama = self::BULAN_LIST[$filterBulanNum];

        $jenjangValid = ['SD', 'SMP', 'SMA'];
        if (! in_array(strtoupper((string) $filterJenjang), $jenjangValid, true)) {
            $filterJenjang = null;
        } else {
            $filterJenjang = strtoupper((string) $filterJenjang);
        }

        // ─── Daftar Asrama untuk Dropdown Filter ─────────────────────────────
        if ($currentUser?->isAdminCabang()) {
            $asramaListFilter = Asrama::where('id', $currentUser->asrama_id)
                ->where('status_aktif', true)->orderBy('nama_asrama')->get();
            $filterAsramaId = $currentUser->asrama_id;
        } else {
            $asramaListFilter = Asrama::where('status_aktif', true)->orderBy('nama_asrama')->get();
        }

        if ($filterAsramaId !== null) {
            $filterAsramaId = (int) $filterAsramaId;
            if (! Asrama::where('id', $filterAsramaId)->exists()) {
                $filterAsramaId = null;
            }
        }

        // ─── Kartu 1: Total Anak Asuh Aktif ──────────────────────────────────
        $anakQuery = AnakAsuh::query()->where('status_aktif', true);
        if ($filterAsramaId) {
            $anakQuery->where('asrama_id', $filterAsramaId);
        } elseif ($currentUser?->isAdminCabang()) {
            $anakQuery->where('asrama_id', $currentUser->asrama_id);
        }
        if ($filterJenjang) {
            $anakQuery->whereRaw('UPPER(jenjang_pendidikan) = ?', [$filterJenjang]);
        }
        $totalAnakAktif = $anakQuery->count();

        // Breakdown jenjang (tidak terpengaruh filter jenjang agar tetap menampilkan semua)
        $anakBase = AnakAsuh::query()->where('status_aktif', true);
        if ($filterAsramaId) {
            $anakBase->where('asrama_id', $filterAsramaId);
        } elseif ($currentUser?->isAdminCabang()) {
            $anakBase->where('asrama_id', $currentUser->asrama_id);
        }
        $jenjangCounts = $anakBase->selectRaw('UPPER(jenjang_pendidikan) as jenjang, COUNT(*) as total')
            ->groupBy('jenjang')->pluck('total', 'jenjang');

        $sdCount = (int) ($jenjangCounts->get('SD') ?? 0);
        $smpCount = (int) ($jenjangCounts->get('SMP') ?? 0);
        $smaCount = (int) ($jenjangCounts->get('SMA') ?? 0);

        // ─── Kartu 2: Asrama Aktif ────────────────────────────────────────────
        $asramaAktifQ = Asrama::query()->where('status_aktif', true);
        if ($currentUser?->isAdminCabang()) {
            $asramaAktifQ->where('id', $currentUser->asrama_id);
        } elseif ($filterAsramaId) {
            $asramaAktifQ->where('id', $filterAsramaId);
        }
        $totalAsramaAktif = $asramaAktifQ->count();

        // ─── Kartu 3 & 4: Evaluasi Periode ───────────────────────────────────
        $evalBase = EvaluasiDiniyah::query()
            ->where('bulan', $filterBulanNama)
            ->where('tahun', $filterTahun);

        if ($filterAsramaId) {
            $evalBase->where('asrama_id', $filterAsramaId);
        } elseif ($currentUser?->isAdminCabang()) {
            $evalBase->where('asrama_id', $currentUser->asrama_id);
        }
        if ($filterJenjang) {
            $evalBase->whereHas('anakAsuh', fn ($q) => $q->whereRaw('UPPER(jenjang_pendidikan) = ?', [$filterJenjang]));
        }

        $totalEvaluasiPeriode = (clone $evalBase)->count();
        $evalDisetujui = (clone $evalBase)->where('status', EvaluasiDiniyah::STATUS_DISETUJUI)->count();
        $evalPendingTotal = (clone $evalBase)->where('status', EvaluasiDiniyah::STATUS_PENDING)->count();

        $persenEvaluasiSelesai = 0;
        $evalStatusTeks = 'Belum ada evaluasi untuk dihitung';
        if ($totalEvaluasiPeriode > 0) {
            $persenEvaluasiSelesai = (int) round(($evalDisetujui / $totalEvaluasiPeriode) * 100);
            $evalStatusTeks = "{$evalDisetujui} dari {$totalEvaluasiPeriode} evaluasi selesai";
        }

        // ─── Grafik 1: Perkembangan Evaluasi 6 Bulan (Line Chart) ────────────
        $grafikPerkembanganLabels = [];
        $grafikPerkembanganData = [];
        $refDate = Carbon::create($filterTahun, $filterBulanNum, 1);

        for ($i = 5; $i >= 0; $i--) {
            $tgl = $refDate->copy()->subMonths($i);
            $bNama = self::BULAN_LIST[(int) $tgl->format('n')];
            $bTahun = (int) $tgl->format('Y');
            $bShort = $tgl->locale('id')->isoFormat('MMM YY');

            $tot = EvaluasiDiniyah::query()
                ->where('bulan', $bNama)->where('tahun', $bTahun)
                ->when($filterAsramaId, fn ($q) => $q->where('asrama_id', $filterAsramaId))
                ->when($currentUser?->isAdminCabang(), fn ($q) => $q->where('asrama_id', $currentUser->asrama_id))
                ->when($filterJenjang, fn ($q) => $q->whereHas('anakAsuh', fn ($sq) => $sq->whereRaw('UPPER(jenjang_pendidikan) = ?', [$filterJenjang])))
                ->count();

            $sel = EvaluasiDiniyah::query()
                ->where('bulan', $bNama)->where('tahun', $bTahun)
                ->where('status', EvaluasiDiniyah::STATUS_DISETUJUI)
                ->when($filterAsramaId, fn ($q) => $q->where('asrama_id', $filterAsramaId))
                ->when($currentUser?->isAdminCabang(), fn ($q) => $q->where('asrama_id', $currentUser->asrama_id))
                ->when($filterJenjang, fn ($q) => $q->whereHas('anakAsuh', fn ($sq) => $sq->whereRaw('UPPER(jenjang_pendidikan) = ?', [$filterJenjang])))
                ->count();

            $grafikPerkembanganLabels[] = $bShort;
            $grafikPerkembanganData[] = $tot > 0 ? round(($sel / $tot) * 100, 1) : 0;
        }

        $grafikPerkembanganAdaData = array_sum($grafikPerkembanganData) > 0;

        // ─── Grafik 2: Distribusi Predikat (Doughnut) ────────────────────────
        $distribusi = EvaluasiDiniyah::query()
            ->where('bulan', $filterBulanNama)->where('tahun', $filterTahun)
            ->where('status', EvaluasiDiniyah::STATUS_DISETUJUI)
            ->when($filterAsramaId, fn ($q) => $q->where('asrama_id', $filterAsramaId))
            ->when($currentUser?->isAdminCabang(), fn ($q) => $q->where('asrama_id', $currentUser->asrama_id))
            ->when($filterJenjang, fn ($q) => $q->whereHas('anakAsuh', fn ($sq) => $sq->whereRaw('UPPER(jenjang_pendidikan) = ?', [$filterJenjang])))
            ->selectRaw('anak_asuh_id, AVG(nilai) as rata_nilai')
            ->groupBy('anak_asuh_id')
            ->get();

        $predikatA = 0;
        $predikatB = 0;
        $predikatC = 0;
        foreach ($distribusi as $row) {
            $rn = (float) $row->rata_nilai;
            if ($rn >= 85) {
                $predikatA++;
            } elseif ($rn >= 70) {
                $predikatB++;
            } else {
                $predikatC++;
            }
        }
        $grafikPredikatAdaData = ($predikatA + $predikatB + $predikatC) > 0;

        // ─── Grafik 3: Kegiatan Harian per Kategori (Bar) ────────────────────
        $kegiatanKategori = ['belajar', 'muhadhoroh', 'olahraga', 'acara_donatur', 'bersih_bersih'];
        $kegiatanLabels = ['Belajar', 'Muhadhoroh', 'Olahraga', 'Acara Donatur', 'Bersih-bersih'];
        $kegiatanData = [];
        $bulanStart = Carbon::create($filterTahun, $filterBulanNum, 1)->startOfMonth();
        $bulanEnd = $bulanStart->copy()->endOfMonth();

        foreach ($kegiatanKategori as $kat) {
            $kegiatanData[] = KegiatanHarian::query()
                ->where('kategori', $kat)
                ->whereBetween('tanggal_kegiatan', [$bulanStart, $bulanEnd])
                ->when($filterAsramaId, fn ($q) => $q->where('asrama_id', $filterAsramaId))
                ->when($currentUser?->isAdminCabang(), fn ($q) => $q->where('asrama_id', $currentUser->asrama_id))
                ->count();
        }
        $grafikKegiatanAdaData = array_sum($kegiatanData) > 0;

        // ─── Grafik 4: Kasus & Pembinaan (Modul Belum Tersedia) ──────────────
        $kasusAnakTotal = 0;
        $pembinaanTotal = 0;
        $kasusModulAda = false;

        // ─── Tabel Monitoring per Asrama ─────────────────────────────────────
        $monitoringQ = Asrama::query()->where('status_aktif', true);
        if ($currentUser?->isAdminCabang()) {
            $monitoringQ->where('id', $currentUser->asrama_id);
        } elseif ($filterAsramaId) {
            $monitoringQ->where('id', $filterAsramaId);
        }

        $asramaMonitoringList = $monitoringQ->withCount([
            'anakAsuh as anak_aktif_count' => function ($q) use ($filterJenjang): void {
                $q->where('status_aktif', true);
                if ($filterJenjang) {
                    $q->whereRaw('UPPER(jenjang_pendidikan) = ?', [$filterJenjang]);
                }
            },
        ])->orderBy('nama_asrama')->get();

        foreach ($asramaMonitoringList as $asrama) {
            $totE = EvaluasiDiniyah::where('asrama_id', $asrama->id)
                ->where('bulan', $filterBulanNama)->where('tahun', $filterTahun)->count();
            $selE = EvaluasiDiniyah::where('asrama_id', $asrama->id)
                ->where('bulan', $filterBulanNama)->where('tahun', $filterTahun)
                ->where('status', EvaluasiDiniyah::STATUS_DISETUJUI)->count();
            $penE = EvaluasiDiniyah::where('asrama_id', $asrama->id)
                ->where('bulan', $filterBulanNama)->where('tahun', $filterTahun)
                ->where('status', EvaluasiDiniyah::STATUS_PENDING)->count();

            $asrama->total_eval = $totE;
            $asrama->selesai_eval = $selE;
            $asrama->pending_eval = $penE;
            $asrama->persen_selesai = $totE > 0 ? (int) round(($selE / $totE) * 100) : 0;

            if ($totE === 0) {
                $asrama->status_laporan = 'Belum Ada Data';
                $asrama->status_warna = 'slate';
            } elseif ($penE > 0) {
                $asrama->status_laporan = 'Perlu Tindak Lanjut';
                $asrama->status_warna = 'amber';
            } else {
                $asrama->status_laporan = 'Lengkap';
                $asrama->status_warna = 'emerald';
            }
        }

        // ─── Kompilasi Data Filter Aktif ─────────────────────────────────────
        $filterAktif = [
            'bulan_num' => $filterBulanNum,
            'bulan_nama' => $filterBulanNama,
            'tahun' => $filterTahun,
            'jenjang' => $filterJenjang,
            'asrama_id' => $filterAsramaId,
        ];

        $tahunOptions = range((int) date('Y') + 1, (int) date('Y') - 4);

        return view('dashboard', compact(
            'filterAktif',
            'asramaListFilter',
            'tahunOptions',
            'totalAnakAktif',
            'sdCount',
            'smpCount',
            'smaCount',
            'totalAsramaAktif',
            'persenEvaluasiSelesai',
            'evalStatusTeks',
            'totalEvaluasiPeriode',
            'evalPendingTotal',
            'grafikPerkembanganLabels',
            'grafikPerkembanganData',
            'grafikPerkembanganAdaData',
            'predikatA',
            'predikatB',
            'predikatC',
            'grafikPredikatAdaData',
            'kegiatanLabels',
            'kegiatanData',
            'grafikKegiatanAdaData',
            'kasusAnakTotal',
            'pembinaanTotal',
            'kasusModulAda',
            'asramaMonitoringList',
        ));
    }
}
