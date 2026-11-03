<?php

// BAGIAN: SHARED - Tipe Data & Status Evaluasi Diniyah

namespace App\Shared\Types;

class EvaluationStatus
{
    public const KKM_DEFAULT = 70.0;

    public const STATUS_PENDING = 'pending';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_PERLU_REVISI = 'perlu_revisi';

    public const TINDAK_LANJUT_BELUM = 'belum_ditindaklanjuti';

    public const TINDAK_LANJUT_PERHATIAN = 'perlu_perhatian';

    public const TINDAK_LANJUT_BIMBINGAN = 'sedang_bimbingan';

    public const TINDAK_LANJUT_TUNTAS = 'tuntas';

    public const DAFTAR_KATEGORI = [
        "Hafalan Al-Qur'an",
        'Hafalan Hadis',
        'Pengetahuan Diniyah',
        'Fiqih & Ibadah',
        'Aqidah & Akhlak',
        'Tajwid & Tahsin',
    ];

    public static function predikat(float $nilai): string
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
}
