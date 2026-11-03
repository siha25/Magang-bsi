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
     * Dapatkan ringkasan statistik untuk dashboard cabang aktif.
     */
    public function getDashboardStats(int $cabangId): array
    {
        $bulanSekarang = now()->translatedFormat('F');
        $tahunSekarang = (int) now()->format('Y');

        $totalSantri = AnakAsuh::query()
            ->where('asrama_id', $cabangId)
            ->where('status_aktif', true)
            ->count();

        $totalKegiatanBulanIni = KegiatanHarian::query()
            ->where('asrama_id', $cabangId)
            ->whereMonth('tanggal_kegiatan', now()->month)
            ->whereYear('tanggal_kegiatan', now()->year)
            ->count();

        $totalEvaluasiBulanIni = EvaluasiDiniyah::query()
            ->where('asrama_id', $cabangId)
            ->where('tahun', $tahunSekarang)
            ->count();

        $rataRataNilai = EvaluasiDiniyah::query()
            ->where('asrama_id', $cabangId)
            ->avg('nilai');

        $kegiatanTerbaru = KegiatanHarian::query()
            ->where('asrama_id', $cabangId)
            ->latest('tanggal_kegiatan')
            ->limit(5)
            ->get();

        $evaluasiTerbaru = EvaluasiDiniyah::query()
            ->where('asrama_id', $cabangId)
            ->with('anakAsuh')
            ->latest('tanggal_evaluasi')
            ->limit(5)
            ->get();

        return [
            'total_santri' => $totalSantri,
            'total_kegiatan_bulan_ini' => $totalKegiatanBulanIni,
            'total_evaluasi_bulan_ini' => $totalEvaluasiBulanIni,
            'rata_rata_nilai' => $rataRataNilai !== null ? round((float) $rataRataNilai, 1) : 0,
            'kegiatan_terbaru' => $kegiatanTerbaru,
            'evaluasi_terbaru' => $evaluasiTerbaru,
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
