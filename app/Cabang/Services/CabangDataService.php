<?php

// BAGIAN: PENGURUS CABANG - Service Logika & Isolasi Data Multi-Cabang

namespace App\Cabang\Services;

use App\Models\AnakAsuh;
use App\Models\EvaluasiDiniyah;
use App\Models\KegiatanHarian;
use App\Shared\Auth\CabangSession;
use Illuminate\Database\Eloquent\Collection;

class CabangDataService
{
    /**
     * Dapatkan ringkasan statistik untuk dashboard cabang:
     * - Jumlah anak asuh aktif
     * - Jumlah sakit di bulan terkini
     * - Jumlah kasus anak di bulan terkini
     * - Grafik hasil ujian diniyah setiap bulannya
     * - Evaluasi hafalan santri
     */
    public function getDashboardStats(int $cabangId): array
    {
        $bulanSekarangNama = now()->translatedFormat('F Y');

        // 1. Jumlah anak asuh yang aktif
        $totalSantri = AnakAsuh::query()
            ->where('asrama_id', $cabangId)
            ->where('status_aktif', true)
            ->count();

        // 2. Jumlah sakit di bulan terkini (default 0 hingga modul kesehatan terintegrasi)
        $totalSakitBulanIni = 0;

        // 3. Jumlah kasus anak di bulan terkini (default 0 hingga modul kasus terintegrasi)
        $totalKasusBulanIni = 0;

        // 4. Grafik hasil ujian diniyah setiap bulannya (6 bulan terakhir)
        $refDate = now();
        $grafikLabels = [];
        $grafikNilaiRata = [];
        $grafikPeserta = [];

        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        for ($i = 5; $i >= 0; $i--) {
            $tgl = $refDate->copy()->subMonths($i);
            $bNama = $daftarBulan[(int) $tgl->format('n')] ?? $tgl->format('F');
            $bTahun = (int) $tgl->format('Y');
            $bShort = $tgl->locale('id')->isoFormat('MMM YY');

            $ujianQuery = EvaluasiDiniyah::query()
                ->where('asrama_id', $cabangId)
                ->where(function ($q): void {
                    $q->where('jenis_evaluasi', '!=', "Hafalan Al-Qur'an")
                        ->orWhereNull('jenis_evaluasi');
                })
                ->where(function ($q) use ($bNama, $bTahun, $tgl): void {
                    $q->where(function ($sq) use ($bNama, $bTahun): void {
                        $sq->where('bulan', $bNama)->where('tahun', $bTahun);
                    })->orWhere(function ($sq) use ($tgl): void {
                        $sq->whereMonth('tanggal_evaluasi', $tgl->month)
                            ->whereYear('tanggal_evaluasi', $tgl->year);
                    });
                });

            $avgNilai = (clone $ujianQuery)->avg('nilai');
            $countPeserta = (clone $ujianQuery)->count();

            $grafikLabels[] = $bShort;
            $grafikNilaiRata[] = $avgNilai !== null ? round((float) $avgNilai, 1) : 0;
            $grafikPeserta[] = $countPeserta;
        }

        $grafikAdaData = array_sum($grafikPeserta) > 0;

        // 5. Evaluasi hafalan santri di cabang ini
        $evaluasiHafalanQuery = EvaluasiDiniyah::query()
            ->where('asrama_id', $cabangId)
            ->where('jenis_evaluasi', "Hafalan Al-Qur'an");

        $totalHafalan = (clone $evaluasiHafalanQuery)->count();
        $rataRataHafalan = (clone $evaluasiHafalanQuery)->avg('nilai');
        $mumtazHafalan = (clone $evaluasiHafalanQuery)->where('nilai', '>=', 85)->count();

        $evaluasiHafalanTerbaru = (clone $evaluasiHafalanQuery)
            ->with(['anakAsuh', 'pengaju'])
            ->latest('tanggal_evaluasi')
            ->latest('id')
            ->limit(5)
            ->get();

        return [
            'total_santri' => $totalSantri,
            'total_sakit_bulan_ini' => $totalSakitBulanIni,
            'total_kasus_bulan_ini' => $totalKasusBulanIni,
            'bulan_terkini_nama' => $bulanSekarangNama,
            'grafik_diniyah' => [
                'labels' => $grafikLabels,
                'data' => $grafikNilaiRata,
                'peserta' => $grafikPeserta,
                'ada_data' => $grafikAdaData,
            ],
            'evaluasi_hafalan' => [
                'list' => $evaluasiHafalanTerbaru,
                'total' => $totalHafalan,
                'rata_rata' => $rataRataHafalan !== null ? round((float) $rataRataHafalan, 1) : 0,
                'mumtaz' => $mumtazHafalan,
            ],
        ];
    }

    /**
     * Dapatkan daftar santri aktif di cabang ini untuk pilihan form input.
     *
     * @return Collection<int, AnakAsuh>
     */
    public function getSantriList(int $cabangId): Collection
    {
        return AnakAsuh::query()
            ->where('asrama_id', $cabangId)
            ->where('status_aktif', true)
            ->orderBy('nama_lengkap')
            ->get();
    }

    /**
     * Simpan kegiatan harian cabang.
     * CabangId selalu diisi dari session server, bukan dari request pengguna.
     */
    public function storeKegiatan(array $data, int $cabangId, int $userId): KegiatanHarian
    {
        $data['asrama_id'] = $cabangId;
        $data['penginput_id'] = $userId;

        return KegiatanHarian::create($data);
    }

    /**
     * Simpan evaluasi hafalan atau diniyah cabang.
     * CabangId diisi otomatis dari session server.
     */
    public function storeEvaluasi(array $data, int $cabangId, int $userId): EvaluasiDiniyah
    {
        // Pastikan santri yang dipilih benar-benar milik cabang ini
        $santri = AnakAsuh::query()
            ->where('id', $data['anak_asuh_id'])
            ->where('asrama_id', $cabangId)
            ->firstOrFail();

        $data['asrama_id'] = $cabangId;
        $data['diajukan_oleh'] = $userId;
        $data['diajukan_pada'] = now();
        $data['status'] = EvaluasiDiniyah::STATUS_DISETUJUI;

        return EvaluasiDiniyah::create($data);
    }

    /**
     * Perbarui evaluasi cabang dengan validasi kepemilikan.
     */
    public function updateEvaluasi(EvaluasiDiniyah $evaluasi, array $data, int $cabangId): EvaluasiDiniyah
    {
        CabangSession::validateAccess($evaluasi->asrama_id);

        if (isset($data['anak_asuh_id'])) {
            AnakAsuh::query()
                ->where('id', $data['anak_asuh_id'])
                ->where('asrama_id', $cabangId)
                ->firstOrFail();
        }

        $data['asrama_id'] = $cabangId;
        $evaluasi->update($data);

        return $evaluasi;
    }
}
