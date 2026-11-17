<?php

namespace App\Services;

use App\Models\AnakAsuh;
use App\Models\Asrama;
use Carbon\Carbon;
use DateTimeInterface;
use InvalidArgumentException;

class NomorIndukGenerator
{
    /**
     * Ekstrak 3 digit angka dari kode asrama (misal: ASR-001 -> "001", ASR-2 -> "002").
     *
     * @throws InvalidArgumentException
     */
    public function extractKodeAsramaDigits(string $kodeAsrama): string
    {
        $trimmed = trim($kodeAsrama);
        if (preg_match('/\d+/', $trimmed, $matches) !== 1) {
            throw new InvalidArgumentException("Kode asrama '{$kodeAsrama}' tidak valid untuk format nomor induk (harus memiliki digit angka).");
        }

        return str_pad($matches[0], 3, '0', STR_PAD_LEFT);
    }

    /**
     * Ekstrak tahun penuh dan 2 digit tahun (YY) dari tanggal masuk asrama.
     *
     * @return array{0: int, 1: string} [tahunPenuh, yy]
     *
     * @throws InvalidArgumentException
     */
    public function extractTahunMasuk(string|DateTimeInterface|null $tanggalMasuk): array
    {
        if (blank($tanggalMasuk)) {
            throw new InvalidArgumentException('Tanggal masuk asrama wajib diisi.');
        }

        try {
            $carbon = $tanggalMasuk instanceof DateTimeInterface
                ? Carbon::instance($tanggalMasuk)
                : Carbon::parse($tanggalMasuk);

            $year = (int) $carbon->format('Y');
            $yy = $carbon->format('y');

            return [$year, $yy];
        } catch (\Throwable $e) {
            throw new InvalidArgumentException('Format tanggal masuk asrama tidak valid.');
        }
    }

    /**
     * Format string nomor induk dengan pola YYAAANN tanpa tanda setrip.
     * Contoh: 2600101 (Tahun 2026, Asrama 001, Urutan 01).
     * Jika urutan <= 99, gunakan 2 digit (01-99).
     * Jika urutan >= 100, perlebar secara konsisten ke 3 digit atau lebih (100, 101, dst.).
     */
    public function formatNomorInduk(string $yy, string $kodeAsramaDigits, int $nomorUrut): string
    {
        $nn = $nomorUrut <= 99 ? sprintf('%02d', $nomorUrut) : (string) $nomorUrut;

        return "{$yy}{$kodeAsramaDigits}{$nn}";
    }

    /**
     * Dapatkan nomor urut berikutnya berdasarkan MAX(urutan) + 1 untuk kombinasi asrama dan tahun masuk.
     * BUKAN menggunakan count() agar urutan tidak mundur/ganda jika ada data yang dihapus.
     */
    public function getNextUrutan(int $asramaId, int $tahunMasuk, string $yy, string $kodeAsramaDigits, bool $withLock = false): int
    {
        $query = AnakAsuh::query()
            ->where('asrama_id', $asramaId)
            ->whereYear('tanggal_masuk', $tahunMasuk);

        if ($withLock) {
            $query->lockForUpdate();
        }

        // 1. Ambil nilai maksimum dari kolom nomor_urut
        $maxFromCol = (int) $query->max('nomor_urut');

        // 2. Periksa juga nomor_induk jika ada data historis tanpa kolom nomor_urut (baik tanpa setrip maupun format lama)
        $highestFromInduk = 0;
        $prefixTanpaSetrip = "{$yy}{$kodeAsramaDigits}";
        $prefixSetrip = "{$yy}-{$kodeAsramaDigits}-";

        $existingIndukList = AnakAsuh::query()
            ->where('asrama_id', $asramaId)
            ->whereYear('tanggal_masuk', $tahunMasuk)
            ->where(function ($q) use ($prefixTanpaSetrip, $prefixSetrip) {
                $q->where('nomor_induk', 'like', "{$prefixTanpaSetrip}%")
                    ->orWhere('nomor_induk', 'like', "{$prefixSetrip}%");
            })
            ->pluck('nomor_induk');

        foreach ($existingIndukList as $induk) {
            $clean = str_replace('-', '', (string) $induk);
            if (str_starts_with($clean, $prefixTanpaSetrip)) {
                $suffix = substr($clean, strlen($prefixTanpaSetrip));
                if (is_numeric($suffix)) {
                    $highestFromInduk = max($highestFromInduk, (int) $suffix);
                }
            }
        }

        return max($maxFromCol, $highestFromInduk) + 1;
    }

    /**
     * Buat pratinjau nomor induk untuk kebutuhan antarmuka (frontend).
     *
     * @return array{nomor_induk: string, nomor_urut: int, tahun: int, yy: string, kode_asrama_digits: string}
     */
    public function preview(int $asramaId, string|DateTimeInterface $tanggalMasuk): array
    {
        $asrama = Asrama::findOrFail($asramaId);
        $kodeDigits = $this->extractKodeAsramaDigits($asrama->kode_asrama);
        [$tahunPenuh, $yy] = $this->extractTahunMasuk($tanggalMasuk);

        $nextUrut = $this->getNextUrutan($asramaId, $tahunPenuh, $yy, $kodeDigits, false);
        $nomorInduk = $this->formatNomorInduk($yy, $kodeDigits, $nextUrut);

        return [
            'nomor_induk' => $nomorInduk,
            'nomor_urut' => $nextUrut,
            'tahun' => $tahunPenuh,
            'yy' => $yy,
            'kode_asrama_digits' => $kodeDigits,
        ];
    }

    /**
     * Hitung ulang dan pastikan nomor induk final aman dari race condition (locking transaksi server).
     * Nilai dari browser tidak dipercaya, nomor dihitung langsung di dalam transaksi database.
     *
     * @return array{nomor_induk: string, nomor_urut: int}
     */
    public function generateForCreation(int $asramaId, string|DateTimeInterface $tanggalMasuk): array
    {
        // Kunci baris asrama untuk memastikan registrasi konkuren pada asrama yang sama dijalankan berurutan (serialized)
        $asrama = Asrama::where('id', $asramaId)->lockForUpdate()->firstOrFail();
        $kodeDigits = $this->extractKodeAsramaDigits($asrama->kode_asrama);
        [$tahunPenuh, $yy] = $this->extractTahunMasuk($tanggalMasuk);

        $nextUrut = $this->getNextUrutan($asramaId, $tahunPenuh, $yy, $kodeDigits, true);
        $nomorInduk = $this->formatNomorInduk($yy, $kodeDigits, $nextUrut);

        // Safety loop ganda: jika nomor induk masih bentrok, increment urutan hingga benar-benar unik
        while (AnakAsuh::where('nomor_induk', $nomorInduk)->exists()) {
            $nextUrut++;
            $nomorInduk = $this->formatNomorInduk($yy, $kodeDigits, $nextUrut);
        }

        return [
            'nomor_induk' => $nomorInduk,
            'nomor_urut' => $nextUrut,
        ];
    }
}
