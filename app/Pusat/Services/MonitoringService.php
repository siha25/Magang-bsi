<?php

// BAGIAN: PENGURUS PUSAT - Service Agregasi Monitoring & Grafik Multi-Cabang

namespace App\Pusat\Services;

use App\Models\AnakAsuh;
use App\Models\Asrama;
use App\Models\EvaluasiDiniyah;
use App\Models\KategoriKegiatan;
use App\Models\KegiatanHarian;
use App\Shared\Types\EvaluationStatus;
use Illuminate\Support\Facades\Schema;

class MonitoringService
{
    /**
     * Dapatkan data ringkasan metrik dan grafik untuk Dashboard Pusat.
     */
    public function getDashboardData(array $filters = []): array
    {
        $kkm = (float) ($filters['kkm'] ?? EvaluationStatus::KKM_DEFAULT);

        // 1. Kartu Ringkasan
        $totalCabang = Asrama::count();
        $totalCabangAktif = Asrama::where('status_aktif', true)->count();
        $totalKegiatan = KegiatanHarian::count();
        $rataRataDiniyah = EvaluasiDiniyah::pengetahuanDiniyah()
            ->whereNotNull('nilai')
            ->avg('nilai');
        $jumlahSantriKurang = EvaluasiDiniyah::pengetahuanDiniyah()
            ->whereNotNull('nilai')
            ->where('nilai', '<', $kkm)
            ->distinct('anak_asuh_id')
            ->count('anak_asuh_id');

        // 2. Data Grafik Perbandingan Antar Cabang
        $cabangs = Asrama::where('status_aktif', true)->orderBy('nama_asrama')->get();

        $cabangLabels = [];
        $cabangKegiatanCounts = [];
        $cabangRataNilai = [];
        $cabangSantriCounts = [];

        foreach ($cabangs as $c) {
            $cabangLabels[] = $c->nama_asrama;
            $cabangKegiatanCounts[] = KegiatanHarian::where('asrama_id', $c->id)->count();
            $avgScore = EvaluasiDiniyah::pengetahuanDiniyah()
                ->where('asrama_id', $c->id)
                ->whereNotNull('nilai')
                ->avg('nilai');
            $cabangRataNilai[] = $avgScore !== null ? round((float) $avgScore, 1) : 0;
            $cabangSantriCounts[] = AnakAsuh::where('asrama_id', $c->id)->where('status_aktif', true)->count();
        }

        // 3. Distribusi Predikat Nilai Seluruh Cabang (khusus Pengetahuan Diniyah)
        $evaluasis = EvaluasiDiniyah::pengetahuanDiniyah()
            ->whereNotNull('nilai')
            ->get(['nilai']);
        $distribusi = [
            'mumtaz' => $evaluasis->where('nilai', '>=', 90)->count(),
            'jayyid_jiddan' => $evaluasis->whereBetween('nilai', [80, 89.99])->count(),
            'jayyid' => $evaluasis->whereBetween('nilai', [70, 79.99])->count(),
            'maqbul' => $evaluasis->whereBetween('nilai', [60, 69.99])->count(),
            'kurang' => $evaluasis->where('nilai', '<', 60)->count(),
        ];

        return [
            'total_cabang' => $totalCabang,
            'total_cabang_aktif' => $totalCabangAktif,
            'total_kegiatan' => $totalKegiatan,
            'rata_rata_diniyah' => $rataRataDiniyah !== null ? round((float) $rataRataDiniyah, 1) : 0,
            'jumlah_santri_kurang' => $jumlahSantriKurang,
            'kkm' => $kkm,
            'cabang_labels' => $cabangLabels,
            'cabang_kegiatan_counts' => $cabangKegiatanCounts,
            'cabang_rata_nilai' => $cabangRataNilai,
            'cabang_santri_counts' => $cabangSantriCounts,
            'distribusi_predikat' => $distribusi,
        ];
    }

    /**
     * Dapatkan data grafik dan tabel untuk Monitoring Kegiatan Harian Seluruh Cabang.
     */
    public function getMonitoringKegiatanData(array $filters = []): array
    {
        $relations = ['asrama', 'penginput'];
        if (KategoriKegiatan::hasTable() && Schema::hasColumn('kegiatan_harian', 'kategori_kegiatan_id')) {
            $relations[] = 'kategoriKegiatan';
        }
        if (Schema::hasTable('dokumentasi_kegiatan')) {
            $relations[] = 'dokumentasi';
        }

        $query = KegiatanHarian::with($relations);

        if (! empty($filters['cabang_id'])) {
            $query->where('asrama_id', $filters['cabang_id']);
        }

        if (! empty($filters['daerah'])) {
            $query->whereHas('asrama', fn ($q) => $q->where('wilayah', $filters['daerah']));
        }

        if (! empty($filters['tanggal_mulai'])) {
            $query->whereDate('tanggal_kegiatan', '>=', $filters['tanggal_mulai']);
        }

        if (! empty($filters['tanggal_selesai'])) {
            $query->whereDate('tanggal_kegiatan', '<=', $filters['tanggal_selesai']);
        }

        if (! empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }

        // Data Agregasi Grafik: Jumlah Kegiatan & Kehadiran per Cabang
        $cabangs = Asrama::where('status_aktif', true)
            ->when(! empty($filters['cabang_id']), fn ($q) => $q->where('id', $filters['cabang_id']))
            ->when(! empty($filters['daerah']), fn ($q) => $q->where('wilayah', $filters['daerah']))
            ->orderBy('nama_asrama')
            ->get();

        $chartLabels = [];
        $chartKegiatan = [];
        $chartKehadiran = [];

        foreach ($cabangs as $c) {
            $subQ = KegiatanHarian::where('asrama_id', $c->id);

            if (! empty($filters['tanggal_mulai'])) {
                $subQ->whereDate('tanggal_kegiatan', '>=', $filters['tanggal_mulai']);
            }
            if (! empty($filters['tanggal_selesai'])) {
                $subQ->whereDate('tanggal_kegiatan', '<=', $filters['tanggal_selesai']);
            }
            if (! empty($filters['kategori'])) {
                $subQ->where('kategori', $filters['kategori']);
            }

            $chartLabels[] = $c->nama_asrama;
            $chartKegiatan[] = (clone $subQ)->count();
            $chartKehadiran[] = (int) ((clone $subQ)->sum('jumlah_peserta') ?? 0);
        }

        $kegiatans = $query->orderByDesc('tanggal_kegiatan')->latest('id')->paginate(10)->withQueryString();

        return [
            'kegiatans' => $kegiatans,
            'chart_labels' => $chartLabels,
            'chart_kegiatan' => $chartKegiatan,
            'chart_kehadiran' => $chartKehadiran,
            'total_kegiatan_filter' => array_sum($chartKegiatan),
            'total_kehadiran_filter' => array_sum($chartKehadiran),
        ];
    }

    /**
     * Dapatkan data grafik dan tabel untuk Monitoring Pengetahuan Diniyah Seluruh Cabang.
     * Hanya menampilkan kategori Pengetahuan Diniyah (bukan Hafalan Al-Qur'an / Hafalan Hadis).
     */
    public function getMonitoringDiniyahData(array $filters = []): array
    {
        // Gabungkan daftar kategori standar dengan kategori non-hafalan yang tercatat di database
        $existingKategori = EvaluasiDiniyah::pengetahuanDiniyah()
            ->whereNotNull('jenis_evaluasi')
            ->where('jenis_evaluasi', '!=', '')
            ->distinct()
            ->pluck('jenis_evaluasi')
            ->all();

        $kategoriPengetahuan = array_values(array_unique(array_merge(
            EvaluationStatus::KATEGORI_PENGETAHUAN_DINIYAH,
            $existingKategori
        )));

        $applyCommonFilters = function ($q) use ($filters): void {
            if (! empty($filters['materi'])) {
                $q->where('jenis_evaluasi', $filters['materi']);
            }
            if (! empty($filters['jenjang'])) {
                $q->whereHas('anakAsuh', fn ($sub) => $sub->where('jenjang_pendidikan', $filters['jenjang']));
            }
            if (! empty($filters['tanggal_mulai'])) {
                $q->whereDate('tanggal_evaluasi', '>=', $filters['tanggal_mulai']);
            }
            if (! empty($filters['tanggal_selesai'])) {
                $q->whereDate('tanggal_evaluasi', '<=', $filters['tanggal_selesai']);
            }
            if (! empty($filters['cari'])) {
                $cari = $filters['cari'];
                $q->where(function ($searchQ) use ($cari): void {
                    $searchQ->where('materi', 'like', "%{$cari}%")
                        ->orWhereHas('anakAsuh', function ($sub) use ($cari): void {
                            $sub->where('nama_lengkap', 'like', "%{$cari}%")
                                ->orWhere('nomor_induk', 'like', "%{$cari}%");
                        });
                });
            }
        };

        $query = EvaluasiDiniyah::with(['asrama', 'anakAsuh', 'pengaju'])
            ->pengetahuanDiniyah();

        if (! empty($filters['cabang_id'])) {
            $query->where('asrama_id', $filters['cabang_id']);
        }

        if (! empty($filters['daerah'])) {
            $query->whereHas('asrama', fn ($q) => $q->where('wilayah', $filters['daerah']));
        }

        $applyCommonFilters($query);

        // 1. Grafik Rata-rata Nilai per Cabang (sinkron dengan Dashboard Pusat)
        $cabangs = Asrama::where('status_aktif', true)
            ->when(! empty($filters['cabang_id']), fn ($q) => $q->where('id', $filters['cabang_id']))
            ->when(! empty($filters['daerah']), fn ($q) => $q->where('wilayah', $filters['daerah']))
            ->orderBy('nama_asrama')
            ->get();

        $cabangLabels = [];
        $cabangRataNilai = [];

        foreach ($cabangs as $c) {
            $subQ = EvaluasiDiniyah::pengetahuanDiniyah()
                ->where('asrama_id', $c->id)
                ->whereNotNull('nilai');

            $applyCommonFilters($subQ);

            $avg = $subQ->avg('nilai');
            $cabangLabels[] = $c->nama_asrama;
            $cabangRataNilai[] = $avg !== null ? round((float) $avg, 1) : 0;
        }

        // 2. Grafik Rata-rata Nilai per Materi (hanya kategori Pengetahuan)
        $materiLabels = [];
        $materiRataNilai = [];
        $targetKategoriMateri = ! empty($filters['materi']) ? [$filters['materi']] : $kategoriPengetahuan;

        foreach ($targetKategoriMateri as $kat) {
            $subMQ = EvaluasiDiniyah::pengetahuanDiniyah()
                ->where('jenis_evaluasi', $kat)
                ->whereNotNull('nilai');

            if (! empty($filters['cabang_id'])) {
                $subMQ->where('asrama_id', $filters['cabang_id']);
            }
            if (! empty($filters['daerah'])) {
                $subMQ->whereHas('asrama', fn ($q) => $q->where('wilayah', $filters['daerah']));
            }

            $applyCommonFilters($subMQ);

            $avgM = $subMQ->avg('nilai');
            $materiLabels[] = $kat;
            $materiRataNilai[] = $avgM !== null ? round((float) $avgM, 1) : 0;
        }

        $evaluasis = $query->latest('tanggal_evaluasi')->latest('id')->paginate(20)->withQueryString();

        return [
            'evaluasis' => $evaluasis,
            'cabang_labels' => $cabangLabels,
            'cabang_rata_nilai' => $cabangRataNilai,
            'materi_labels' => $materiLabels,
            'materi_rata_nilai' => $materiRataNilai,
        ];
    }

    /**
     * Dapatkan daftar santri/cabang yang nilainya kurang dari batas minimal KKM untuk Evaluasi Ringan Pusat.
     */
    public function getEvaluasiRinganData(array $filters = [], float $kkm = 70.0): array
    {
        $query = EvaluasiDiniyah::with(['asrama', 'anakAsuh', 'penilai'])
            ->pengetahuanDiniyah()
            ->whereNotNull('nilai')
            ->where('nilai', '<', $kkm);

        if (! empty($filters['cabang_id'])) {
            $query->where('asrama_id', $filters['cabang_id']);
        }

        if (! empty($filters['daerah'])) {
            $query->whereHas('asrama', fn ($q) => $q->where('wilayah', $filters['daerah']));
        }

        if (! empty($filters['status_tindak_lanjut'])) {
            $query->where('status_tindak_lanjut', $filters['status_tindak_lanjut']);
        }

        $daftarKurang = $query->latest('tanggal_evaluasi')->latest('id')->paginate(10)->withQueryString();

        $totalKurang = (clone $query)->count();
        $totalBelumDitindak = (clone $query)->where(function ($q): void {
            $q->whereNull('status_tindak_lanjut')
                ->orWhere('status_tindak_lanjut', EvaluationStatus::TINDAK_LANJUT_BELUM);
        })->count();

        return [
            'daftar_kurang' => $daftarKurang,
            'kkm' => $kkm,
            'total_kurang' => $totalKurang,
            'total_belum_ditindak' => $totalBelumDitindak,
        ];
    }

    /**
     * Menyimpan catatan evaluasi ringan dan status tindak lanjut oleh Pengurus Pusat.
     */
    public function simpanCatatanEvaluasi(EvaluasiDiniyah $evaluasi, string $catatan, string $statusTindakLanjut, int $penilaiId): void
    {
        $evaluasi->update([
            'catatan_evaluasi_pusat' => $catatan,
            'status_tindak_lanjut' => $statusTindakLanjut,
            'dinilai_oleh' => $penilaiId,
            'dinilai_pada' => now(),
        ]);
    }
}
