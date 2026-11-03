<?php

// BAGIAN: SHARED - Helper Format Tanggal & Bulan

namespace App\Shared\Utils;

use Carbon\Carbon;

class DateHelper
{
    public const BULAN_LIST = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public static function getNamaBulan(int $bulanNum): string
    {
        return self::BULAN_LIST[$bulanNum] ?? 'Januari';
    }

    public static function formatTanggalIndo(string|Carbon|null $date): string
    {
        if (! $date) {
            return '-';
        }

        $c = $date instanceof Carbon ? $date : Carbon::parse($date);

        return $c->locale('id')->isoFormat('D MMMM Y');
    }
}
