<?php

// BAGIAN: PENGURUS PUSAT - Pusat Laporan & Rekapitulasi Seluruh Cabang

namespace App\Pusat\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AnakAsuh;
use App\Models\Asrama;
use App\Models\EvaluasiDiniyah;
use App\Models\KategoriKegiatan;
use App\Models\KegiatanHarian;
use App\Shared\Types\EvaluationStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * Daftar jenis tab laporan yang tersedia.
     *
     * @var array<string, string>
     */
    public const JENIS_LAPORAN = [
        'ringkasan' => 'Rekapitulasi Asrama',
        'kegiatan' => 'Kegiatan Harian',
        'hafalan_quran' => "Hafalan Al-Qur'an",
        'hafalan_hadis' => 'Hafalan Hadis',
        'pengetahuan_diniyah' => 'Pengetahuan Diniyah',
    ];

    /**
     * Menampilkan halaman pusat laporan & rekapitulasi multi-cabang.
     */
    public function index(Request $request): View
    {
        $filters = $this->extractFilters($request);

        $cabangOptions = Asrama::where('status_aktif', true)->orderBy('nama_asrama')->get();
        $wilayahOptions = Asrama::query()
            ->whereNotNull('wilayah')
            ->where('wilayah', '!=', '')
            ->distinct()
            ->orderBy('wilayah')
            ->pluck('wilayah');
        $jenjangOptions = ['SD', 'SMP', 'SMA'];

        $summary = $this->buildSummary($filters);
        $rekapAsrama = $this->buildRekapAsrama($filters);

        $items = null;
        if ($filters['jenis'] !== 'ringkasan') {
            $items = $this->buildDetailQuery($filters)->paginate(20)->withQueryString();
        }

        return view('pusat.views.laporan.index', [
            'filters' => $filters,
            'jenisOptions' => self::JENIS_LAPORAN,
            'cabangOptions' => $cabangOptions,
            'wilayahOptions' => $wilayahOptions,
            'jenjangOptions' => $jenjangOptions,
            'summary' => $summary,
            'rekapAsrama' => $rekapAsrama,
            'items' => $items,
        ]);
    }

    /**
     * Mengekspor laporan ke file CSV yang kompatibel dengan Microsoft Excel.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $filters = $this->extractFilters($request);
        $jenis = $filters['jenis'];
        $filename = 'laporan-'.$jenis.'-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($filters, $jenis): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            // UTF-8 BOM agar Excel langsung mengenali karakter UTF-8 dengan benar
            fwrite($handle, "\xEF\xBB\xBF");

            if ($jenis === 'ringkasan') {
                fputcsv($handle, [
                    'No',
                    'Kode Asrama',
                    'Nama Asrama',
                    'Wilayah',
                    'Santri Aktif',
                    'Jumlah Kegiatan',
                    'Total Kehadiran',
                    'Hafalan Quran Total',
                    'Hafalan Quran Disetujui',
                    'Hafalan Hadis Total',
                    'Hafalan Hadis Disetujui',
                    'Rata-Rata Nilai Diniyah',
                    'Status Laporan',
                ]);

                $rekap = $this->buildRekapAsrama($filters);
                foreach ($rekap as $index => $row) {
                    fputcsv($handle, [
                        $index + 1,
                        $row['kode_asrama'],
                        $row['nama_asrama'],
                        $row['wilayah'],
                        $row['santri_aktif'],
                        $row['total_kegiatan'],
                        $row['total_kehadiran'],
                        $row['quran_total'],
                        $row['quran_disetujui'],
                        $row['hadis_total'],
                        $row['hadis_disetujui'],
                        $row['rata_diniyah'] !== null ? $row['rata_diniyah'] : '-',
                        $row['status_laporan'],
                    ]);
                }
            } else {
                [$headers, $rows] = $this->buildDetailCsvRows($filters);
                fputcsv($handle, $headers);
                foreach ($rows as $csvRow) {
                    fputcsv($handle, $csvRow);
                }
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Mengambil dan menormalkan parameter filter dari request.
     *
     * @return array{jenis: string, cabang_id: ?string, wilayah: string, jenjang: string, tanggal_mulai: ?string, tanggal_selesai: ?string}
     */
    protected function extractFilters(Request $request): array
    {
        $jenis = $request->string('jenis')->trim()->toString();
        if (! array_key_exists($jenis, self::JENIS_LAPORAN)) {
            $jenis = 'ringkasan';
        }

        return [
            'jenis' => $jenis,
            'cabang_id' => $request->input('cabang_id') ?: null,
            'wilayah' => $request->string('wilayah')->trim()->toString(),
            'jenjang' => $request->string('jenjang')->trim()->toString(),
            'tanggal_mulai' => $request->input('tanggal_mulai') ?: null,
            'tanggal_selesai' => $request->input('tanggal_selesai') ?: null,
        ];
    }

    /**
     * Menghitung ringkasan metrik eksekutif berdasarkan filter aktif.
     *
     * @param  array{jenis: string, cabang_id: ?string, wilayah: string, jenjang: string, tanggal_mulai: ?string, tanggal_selesai: ?string}  $filters
     * @return array<string, int|float>
     */
    protected function buildSummary(array $filters): array
    {
        $asramaIds = $this->filteredAsramaQuery($filters)->pluck('id');

        $anakQuery = AnakAsuh::query()
            ->whereIn('asrama_id', $asramaIds)
            ->where('status_aktif', true);

        if ($filters['jenjang'] !== '') {
            $anakQuery->where('jenjang_pendidikan', $filters['jenjang']);
        }

        $totalAnakAktif = (clone $anakQuery)->count();
        $sdCount = (clone $anakQuery)->where('jenjang_pendidikan', 'SD')->count();
        $smpCount = (clone $anakQuery)->where('jenjang_pendidikan', 'SMP')->count();
        $smaCount = (clone $anakQuery)->where('jenjang_pendidikan', 'SMA')->count();

        $kegiatanQuery = $this->applyKegiatanFilters(KegiatanHarian::query(), $filters);
        $totalKegiatan = (clone $kegiatanQuery)->count();
        $totalKehadiran = (int) ((clone $kegiatanQuery)->sum('jumlah_peserta') ?? 0);

        $quranQuery = $this->applyEvaluasiFilters(
            EvaluasiDiniyah::query()->where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_QURAN),
            $filters
        );
        $totalQuran = (clone $quranQuery)->count();
        $quranDisetujui = (clone $quranQuery)->where('status', EvaluasiDiniyah::STATUS_DISETUJUI)->count();
        $quranPending = (clone $quranQuery)->where('status', EvaluasiDiniyah::STATUS_PENDING)->count();

        $hadisQuery = $this->applyEvaluasiFilters(
            EvaluasiDiniyah::query()->where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_HADIS),
            $filters
        );
        $totalHadis = (clone $hadisQuery)->count();
        $hadisDisetujui = (clone $hadisQuery)->where('status', EvaluasiDiniyah::STATUS_DISETUJUI)->count();
        $hadisPending = (clone $hadisQuery)->where('status', EvaluasiDiniyah::STATUS_PENDING)->count();

        $diniyahQuery = $this->applyEvaluasiFilters(
            EvaluasiDiniyah::query()->pengetahuanDiniyah()->whereNotNull('nilai'),
            $filters
        );
        $totalDiniyah = (clone $diniyahQuery)->count();
        $rataNilaiDiniyah = (clone $diniyahQuery)->avg('nilai');
        $diniyahBawahKkm = (clone $diniyahQuery)->where('nilai', '<', EvaluationStatus::KKM_DEFAULT)->count();

        return [
            'total_asrama_aktif' => $asramaIds->count(),
            'total_anak_aktif' => $totalAnakAktif,
            'sd_count' => $sdCount,
            'smp_count' => $smpCount,
            'sma_count' => $smaCount,
            'total_kegiatan' => $totalKegiatan,
            'total_kehadiran' => $totalKehadiran,
            'total_hafalan_quran' => $totalQuran,
            'quran_disetujui' => $quranDisetujui,
            'quran_pending' => $quranPending,
            'total_hafalan_hadis' => $totalHadis,
            'hadis_disetujui' => $hadisDisetujui,
            'hadis_pending' => $hadisPending,
            'total_diniyah' => $totalDiniyah,
            'rata_nilai_diniyah' => $rataNilaiDiniyah !== null ? round((float) $rataNilaiDiniyah, 1) : 0,
            'diniyah_bawah_kkm' => $diniyahBawahKkm,
        ];
    }

    /**
     * Menyusun rekapitulasi terpadu per asrama aktif.
     *
     * @param  array{jenis: string, cabang_id: ?string, wilayah: string, jenjang: string, tanggal_mulai: ?string, tanggal_selesai: ?string}  $filters
     * @return Collection<int, array<string, mixed>>
     */
    protected function buildRekapAsrama(array $filters): Collection
    {
        $asramas = $this->filteredAsramaQuery($filters)
            ->orderBy('nama_asrama')
            ->get();

        return $asramas->map(function (Asrama $asrama) use ($filters): array {
            $branchFilters = array_merge($filters, ['cabang_id' => (string) $asrama->id]);

            $santriAktif = AnakAsuh::query()
                ->where('asrama_id', $asrama->id)
                ->where('status_aktif', true)
                ->when($filters['jenjang'] !== '', fn ($q) => $q->where('jenjang_pendidikan', $filters['jenjang']))
                ->count();

            $kegQuery = $this->applyKegiatanFilters(KegiatanHarian::query(), $branchFilters);
            $totalKegiatan = (clone $kegQuery)->count();
            $totalKehadiran = (int) ((clone $kegQuery)->sum('jumlah_peserta') ?? 0);

            $quranQuery = $this->applyEvaluasiFilters(
                EvaluasiDiniyah::query()->where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_QURAN),
                $branchFilters
            );
            $quranTotal = (clone $quranQuery)->count();
            $quranDisetujui = (clone $quranQuery)->where('status', EvaluasiDiniyah::STATUS_DISETUJUI)->count();
            $quranPending = (clone $quranQuery)->where('status', EvaluasiDiniyah::STATUS_PENDING)->count();

            $hadisQuery = $this->applyEvaluasiFilters(
                EvaluasiDiniyah::query()->where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_HADIS),
                $branchFilters
            );
            $hadisTotal = (clone $hadisQuery)->count();
            $hadisDisetujui = (clone $hadisQuery)->where('status', EvaluasiDiniyah::STATUS_DISETUJUI)->count();
            $hadisPending = (clone $hadisQuery)->where('status', EvaluasiDiniyah::STATUS_PENDING)->count();

            $diniyahQuery = $this->applyEvaluasiFilters(
                EvaluasiDiniyah::query()->pengetahuanDiniyah()->whereNotNull('nilai'),
                $branchFilters
            );
            $diniyahTotal = (clone $diniyahQuery)->count();
            $avgDiniyah = (clone $diniyahQuery)->avg('nilai');
            $rataDiniyah = $avgDiniyah !== null ? round((float) $avgDiniyah, 1) : null;

            $totalData = $totalKegiatan + $quranTotal + $hadisTotal + $diniyahTotal;
            $totalPending = $quranPending + $hadisPending;

            if ($totalData === 0) {
                $statusLaporan = 'Belum Ada Data';
                $statusWarna = 'slate';
            } elseif ($totalPending > 0) {
                $statusLaporan = 'Perlu Review';
                $statusWarna = 'amber';
            } else {
                $statusLaporan = 'Lengkap';
                $statusWarna = 'emerald';
            }

            return [
                'id' => $asrama->id,
                'kode_asrama' => $asrama->kode_asrama,
                'nama_asrama' => $asrama->nama_asrama,
                'wilayah' => $asrama->wilayah ?: '-',
                'santri_aktif' => $santriAktif,
                'total_kegiatan' => $totalKegiatan,
                'total_kehadiran' => $totalKehadiran,
                'quran_total' => $quranTotal,
                'quran_disetujui' => $quranDisetujui,
                'hadis_total' => $hadisTotal,
                'hadis_disetujui' => $hadisDisetujui,
                'diniyah_total' => $diniyahTotal,
                'rata_diniyah' => $rataDiniyah,
                'status_laporan' => $statusLaporan,
                'status_warna' => $statusWarna,
            ];
        });
    }

    /**
     * Query detail terfilter untuk tab laporan selain ringkasan.
     *
     * @param  array{jenis: string, cabang_id: ?string, wilayah: string, jenjang: string, tanggal_mulai: ?string, tanggal_selesai: ?string}  $filters
     */
    protected function buildDetailQuery(array $filters): Builder
    {
        if ($filters['jenis'] === 'kegiatan') {
            $relations = ['asrama', 'penginput'];
            if (KategoriKegiatan::hasTable() && Schema::hasColumn('kegiatan_harian', 'kategori_kegiatan_id')) {
                $relations[] = 'kategoriKegiatan';
            }

            return $this->applyKegiatanFilters(KegiatanHarian::with($relations), $filters)
                ->orderByDesc('tanggal_kegiatan')
                ->latest('id');
        }

        $query = EvaluasiDiniyah::with(['asrama', 'anakAsuh', 'pengaju', 'penilai']);

        if ($filters['jenis'] === 'hafalan_quran') {
            $query->where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_QURAN);
        } elseif ($filters['jenis'] === 'hafalan_hadis') {
            $query->where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_HADIS);
        } else {
            $query->pengetahuanDiniyah();
        }

        return $this->applyEvaluasiFilters($query, $filters)
            ->latest('tanggal_evaluasi')
            ->latest('id');
    }

    /**
     * Menyusun header dan baris CSV untuk tab detail laporan.
     *
     * @param  array{jenis: string, cabang_id: ?string, wilayah: string, jenjang: string, tanggal_mulai: ?string, tanggal_selesai: ?string}  $filters
     * @return array{0: list<string>, 1: array<int, list<mixed>>}
     */
    protected function buildDetailCsvRows(array $filters): array
    {
        $records = $this->buildDetailQuery($filters)->get();

        if ($filters['jenis'] === 'kegiatan') {
            $headers = [
                'No',
                'Tanggal',
                'Asrama / Cabang',
                'Wilayah',
                'Nama Kegiatan',
                'Kategori',
                'Jam',
                'Jumlah Peserta',
                'Penginput',
            ];

            $rows = $records->values()->map(function (KegiatanHarian $keg, int $index): array {
                $jam = trim(($keg->jam_mulai_format ?: '-').' - '.($keg->jam_selesai_format ?: '-'));

                return [
                    $index + 1,
                    $keg->tanggal_kegiatan?->format('Y-m-d') ?? '-',
                    $keg->asrama?->nama_asrama ?? '-',
                    $keg->asrama?->wilayah ?? '-',
                    $keg->nama_kegiatan,
                    $keg->kategori_label,
                    $jam,
                    $keg->jumlah_peserta,
                    $keg->penginput?->name ?? 'Staff Cabang',
                ];
            })->all();

            return [$headers, $rows];
        }

        if ($filters['jenis'] === 'pengetahuan_diniyah') {
            $headers = [
                'No',
                'Tanggal',
                'No. Induk',
                'Nama Anak Asuh',
                'Jenjang',
                'Asrama / Cabang',
                'Bidang',
                'Materi',
                'Nilai',
                'Predikat',
                'Status',
            ];

            $rows = $records->values()->map(function (EvaluasiDiniyah $ev, int $index): array {
                return [
                    $index + 1,
                    $ev->tanggal_evaluasi?->format('Y-m-d') ?? '-',
                    $ev->anakAsuh?->nomor_induk ?? '-',
                    $ev->anakAsuh?->nama_lengkap ?? '-',
                    $ev->anakAsuh?->jenjang_pendidikan ?? '-',
                    $ev->asrama?->nama_asrama ?? '-',
                    $ev->jenis_evaluasi,
                    $ev->materi,
                    $ev->nilai !== null ? $ev->nilai : '-',
                    $ev->predikat ?? '-',
                    $ev->status,
                ];
            })->all();

            return [$headers, $rows];
        }

        $headers = [
            'No',
            'Tanggal',
            'No. Induk',
            'Nama Anak Asuh',
            'Jenjang',
            'Asrama / Cabang',
            'Jenis',
            'Materi',
            'Predikat Cabang',
            'Predikat Akhir Pusat',
            'Status',
        ];

        $rows = $records->values()->map(function (EvaluasiDiniyah $ev, int $index): array {
            return [
                $index + 1,
                $ev->tanggal_evaluasi?->format('Y-m-d') ?? '-',
                $ev->anakAsuh?->nomor_induk ?? '-',
                $ev->anakAsuh?->nama_lengkap ?? '-',
                $ev->anakAsuh?->jenjang_pendidikan ?? '-',
                $ev->asrama?->nama_asrama ?? '-',
                $ev->jenis_evaluasi,
                $ev->materi,
                $ev->predikat ?? '-',
                $ev->predikat_akhir ?? '-',
                $ev->status,
            ];
        })->all();

        return [$headers, $rows];
    }

    /**
     * Query asrama aktif sesuai filter cabang_id dan wilayah.
     *
     * @param  array{jenis: string, cabang_id: ?string, wilayah: string, jenjang: string, tanggal_mulai: ?string, tanggal_selesai: ?string}  $filters
     */
    protected function filteredAsramaQuery(array $filters): Builder
    {
        return Asrama::query()
            ->where('status_aktif', true)
            ->when(! empty($filters['cabang_id']), fn (Builder $q) => $q->where('id', $filters['cabang_id']))
            ->when($filters['wilayah'] !== '', fn (Builder $q) => $q->where('wilayah', $filters['wilayah']));
    }

    /**
     * Terapkan filter cabang, wilayah, dan tanggal pada query KegiatanHarian.
     *
     * @param  array{jenis: string, cabang_id: ?string, wilayah: string, jenjang: string, tanggal_mulai: ?string, tanggal_selesai: ?string}  $filters
     */
    protected function applyKegiatanFilters(Builder $query, array $filters): Builder
    {
        if (! empty($filters['cabang_id'])) {
            $query->where('asrama_id', $filters['cabang_id']);
        }
        if ($filters['wilayah'] !== '') {
            $query->whereHas('asrama', fn (Builder $q) => $q->where('wilayah', $filters['wilayah']));
        }
        if (! empty($filters['tanggal_mulai'])) {
            $query->whereDate('tanggal_kegiatan', '>=', $filters['tanggal_mulai']);
        }
        if (! empty($filters['tanggal_selesai'])) {
            $query->whereDate('tanggal_kegiatan', '<=', $filters['tanggal_selesai']);
        }

        return $query;
    }

    /**
     * Terapkan filter cabang, wilayah, jenjang, dan tanggal pada query EvaluasiDiniyah.
     *
     * @param  array{jenis: string, cabang_id: ?string, wilayah: string, jenjang: string, tanggal_mulai: ?string, tanggal_selesai: ?string}  $filters
     */
    protected function applyEvaluasiFilters(Builder $query, array $filters): Builder
    {
        if (! empty($filters['cabang_id'])) {
            $query->where('asrama_id', $filters['cabang_id']);
        }
        if ($filters['wilayah'] !== '') {
            $query->whereHas('asrama', fn (Builder $q) => $q->where('wilayah', $filters['wilayah']));
        }
        if ($filters['jenjang'] !== '') {
            $query->whereHas('anakAsuh', fn (Builder $q) => $q->where('jenjang_pendidikan', $filters['jenjang']));
        }
        if (! empty($filters['tanggal_mulai'])) {
            $query->whereDate('tanggal_evaluasi', '>=', $filters['tanggal_mulai']);
        }
        if (! empty($filters['tanggal_selesai'])) {
            $query->whereDate('tanggal_evaluasi', '<=', $filters['tanggal_selesai']);
        }

        return $query;
    }
}
