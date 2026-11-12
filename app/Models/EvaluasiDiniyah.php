<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class EvaluasiDiniyah extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_PERLU_REVISI = 'perlu_revisi';

    public const KATEGORI_QURAN = "Hafalan Al-Qur'an";

    public const KATEGORI_HADIS = 'Hafalan Hadis';

    public const KATEGORI_PENGETAHUAN = 'Pengetahuan Diniyah';

    public const KATEGORI_FIQIH = 'Fiqih & Ibadah';

    public const KATEGORI_AQIDAH = 'Aqidah & Akhlak';

    public const KATEGORI_TAJWID = 'Tajwid & Tahsin';

    protected $table = 'evaluasi_diniyah';

    protected $fillable = [
        'asrama_id',
        'anak_asuh_id',
        'jenis_evaluasi',
        'materi',
        'tanggal_evaluasi',
        'nilai',
        'predikat',
        'catatan_pengaju',
        'status',
        'catatan_revisi',
        'catatan_evaluasi_pusat',
        'status_tindak_lanjut',
        'diajukan_oleh',
        'diajukan_pada',
        'dinilai_oleh',
        'dinilai_pada',
        'bulan',
        'tahun',
        'semester',
        'predikat_akhir',
        'dokumen_bukti',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_evaluasi' => 'date',
            'diajukan_pada' => 'datetime',
            'dinilai_pada' => 'datetime',
            'nilai' => 'float',
            'tahun' => 'integer',
        ];
    }

    /**
     * Jenis evaluasi yang menggunakan predikat awal A/B/C (input manual Staff Cabang).
     * Predikat TIDAK dihitung otomatis dari nilai numerik untuk jenis ini.
     *
     * @var list<string>
     */
    public const JENIS_PREDIKAT_MANUAL = [
        self::KATEGORI_QURAN,
        self::KATEGORI_HADIS,
    ];

    protected static function booted(): void
    {
        static::saving(function (EvaluasiDiniyah $model): void {
            // Hafalan Al-Qur'an & Hafalan Hadis: predikat diisi manual oleh Staff Cabang (A/B/C).
            // Tidak dihitung ulang dari nilai numerik agar tidak menimpa input.
            // Nilai Diniyah lain: predikat dihitung otomatis dari nilai numerik.
            $isHafalanJenis = in_array($model->jenis_evaluasi, self::JENIS_PREDIKAT_MANUAL, true);

            if (! $isHafalanJenis && $model->nilai !== null && empty($model->predikat)) {
                $model->predikat = static::hitungPredikat((float) $model->nilai);
            }

            if ($model->tanggal_evaluasi) {
                $date = Carbon::parse($model->tanggal_evaluasi);
                if (empty($model->bulan)) {
                    $daftarBulan = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                    ];
                    $model->bulan = $daftarBulan[(int) $date->format('n')] ?? $date->format('F');
                }
                if (empty($model->tahun)) {
                    $model->tahun = (int) $date->format('Y');
                }
                if (empty($model->semester)) {
                    $model->semester = ((int) $date->format('n') <= 6) ? 'Genap' : 'Ganjil';
                }
            }

            if (empty($model->status)) {
                $model->status = self::STATUS_PENDING;
            }
        });
    }

    /**
     * Hitung predikat berdasarkan nilai numerik.
     * Digunakan untuk modul Nilai Diniyah (bukan Hafalan Al-Qur'an / Hafalan Hadis).
     * Batas Mumtaz: nilai >= 90.
     */
    public static function hitungPredikat(float $nilai): string
    {
        if ($nilai >= 90) {
            return 'Mumtaz (A)';
        }
        if ($nilai >= 80) {
            return 'Jayyid Jiddan (B)';
        }
        if ($nilai >= 70) {
            return 'Jayyid (C)';
        }
        if ($nilai >= 60) {
            return 'Maqbul (D)';
        }

        return 'Perlu Bimbingan (E)';
    }

    /**
     * Validasi bahwa nilai predikat hafalan (awal/akhir) hanya boleh A, B, atau C.
     */
    public static function isPredikatHafalanValid(?string $predikat): bool
    {
        return in_array($predikat, ['A', 'B', 'C'], true);
    }

    /**
     * Apakah record ini boleh diedit oleh Staff Cabang (hanya ketika masih pending).
     */
    public function isBisaDieditCabang(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function asrama(): BelongsTo
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }

    public function anakAsuh(): BelongsTo
    {
        return $this->belongsTo(AnakAsuh::class, 'anak_asuh_id');
    }

    public function pengaju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function penilai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dinilai_oleh');
    }

    /**
     * Scope data berdasarkan peran pengguna (Admin Cabang hanya melihat asramanya).
     *
     * @param  Builder<EvaluasiDiniyah>  $query
     * @return Builder<EvaluasiDiniyah>
     */
    public function scopeByRole(Builder $query): Builder
    {
        $user = Auth::user();

        if ($user?->isAdminCabang()) {
            return $query->where('asrama_id', $user->asrama_id);
        }

        return $query;
    }

    /**
     * @param  Builder<EvaluasiDiniyah>  $query
     * @return Builder<EvaluasiDiniyah>
     */
    public function scopeDisetujui(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DISETUJUI);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isDisetujui(): bool
    {
        return $this->status === self::STATUS_DISETUJUI;
    }

    public function isPerluRevisi(): bool
    {
        return $this->status === self::STATUS_PERLU_REVISI;
    }
}
