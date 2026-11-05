<?php

// BAGIAN: SHARED - Manajemen Sesi & Konteks Multi-Cabang

namespace App\Shared\Auth;

use App\Models\Asrama;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CabangSession
{
    public const SESSION_KEY = 'cabang_id';

    /**
     * Dapatkan ID cabang/asrama aktif dari session atau akun login.
     */
    public static function getId(): ?int
    {
        $sessionCabangId = Session::get(self::SESSION_KEY);
        if ($sessionCabangId !== null && (int) $sessionCabangId > 0) {
            return (int) $sessionCabangId;
        }

        $user = Auth::user();
        if ($user && $user->asrama_id) {
            Session::put(self::SESSION_KEY, (int) $user->asrama_id);

            return (int) $user->asrama_id;
        }

        return null;
    }

    /**
     * Simpan ID cabang ke dalam session pengguna.
     */
    public static function setId(int $cabangId): void
    {
        Session::put(self::SESSION_KEY, $cabangId);
    }

    /**
     * Dapatkan model Asrama/Cabang yang sedang aktif.
     */
    public static function getCabang(): ?Asrama
    {
        $id = self::getId();
        if (! $id) {
            return null;
        }

        return Asrama::find($id);
    }

    /**
     * Dapatkan nama cabang yang sedang aktif.
     */
    public static function getNamaCabang(): string
    {
        $cabang = self::getCabang();

        return $cabang?->nama_asrama ?? 'Cabang';
    }

    /**
     * Dapatkan wilayah/daerah dari cabang yang sedang aktif.
     */
    public static function getDaerah(): string
    {
        $cabang = self::getCabang();

        return $cabang?->wilayah ?? '-';
    }

    /**
     * Segarkan referensi data cabang di dalam sesi.
     */
    public static function refresh(): void
    {
        $id = self::getId();
        if ($id) {
            self::setId($id);
        }
    }

    /**
     * Validasi hak akses cabang di sisi server.
     * Mencegah admin cabang mengakses atau memanipulasi data cabang lain lewat URL/API.
     *
     * @throws HttpException
     */
    public static function validateAccess(?int $targetAsramaId): void
    {
        $user = Auth::user();

        // Pengurus Pusat memiliki akses global ke seluruh cabang
        if (! $user || $user->isAdminPusat() || $user->isPimpinanPusat()) {
            return;
        }

        // Pengurus Cabang hanya boleh mengakses data cabangnya sendiri
        $currentCabangId = self::getId();
        if ($targetAsramaId === null || (int) $targetAsramaId !== (int) $currentCabangId) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk melihat atau memanipulasi data cabang lain.');
        }
    }
}
