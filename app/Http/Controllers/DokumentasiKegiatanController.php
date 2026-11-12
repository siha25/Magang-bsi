<?php

namespace App\Http\Controllers;

use App\Models\DokumentasiKegiatan;
use App\Models\KegiatanHarian;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DokumentasiKegiatanController extends Controller
{
    /**
     * Tampilkan file foto dokumentasi kegiatan (privat).
     * Memeriksa otorisasi pengguna & akses asrama.
     */
    public function showFile(int $id): BinaryFileResponse|StreamedResponse|Response
    {
        $user = Auth::user();
        if (! $user) {
            abort(401, 'Silakan login terlebih dahulu untuk mengakses dokumentasi kegiatan.');
        }

        $dokumentasi = DokumentasiKegiatan::with('kegiatan')->findOrFail($id);
        $kegiatan = $dokumentasi->kegiatan;

        if (! $kegiatan) {
            abort(404, 'Data kegiatan terkait tidak ditemukan.');
        }

        // Pembatasan akses:
        // Staff Cabang hanya boleh melihat dokumentasi asramanya sendiri
        if ($user->isAdminCabang()) {
            if (empty($user->asrama_id) || (int) $user->asrama_id !== (int) $kegiatan->asrama_id) {
                abort(403, 'Anda tidak memiliki hak akses untuk melihat dokumentasi kegiatan dari asrama lain.');
            }
        } elseif (! $user->isAdminPusat() && ! $user->isPimpinanPusat()) {
            abort(403, 'Peran akun Anda tidak memiliki izin untuk melihat dokumentasi kegiatan.');
        }

        // Ambil file dari storage privat ('local' -> storage/app/private/)
        if (Storage::disk('local')->exists($dokumentasi->path_file)) {
            return Storage::disk('local')->response(
                $dokumentasi->path_file,
                $dokumentasi->nama_asli ?? 'dokumentasi.jpg'
            );
        }

        // Fallback jika file tersimpan di disk public
        if (Storage::disk('public')->exists($dokumentasi->path_file)) {
            return Storage::disk('public')->response(
                $dokumentasi->path_file,
                $dokumentasi->nama_asli ?? 'dokumentasi.jpg'
            );
        }

        abort(404, 'Berkas foto dokumentasi tidak ditemukan pada penyimpanan server.');
    }

    /**
     * Tampilkan file foto dokumentasi historis (kolom legacy foto_dokumentasi).
     * Memeriksa otorisasi pengguna & akses asrama secara aman.
     */
    public function showLegacyFoto(int $id): BinaryFileResponse|StreamedResponse|Response
    {
        $user = Auth::user();
        if (! $user) {
            abort(401, 'Silakan login terlebih dahulu untuk mengakses dokumentasi kegiatan.');
        }

        $kegiatan = KegiatanHarian::findOrFail($id);

        if ($user->isAdminCabang()) {
            if (empty($user->asrama_id) || (int) $user->asrama_id !== (int) $kegiatan->asrama_id) {
                abort(403, 'Anda tidak memiliki hak akses untuk melihat dokumentasi kegiatan dari asrama lain.');
            }
        } elseif (! $user->isAdminPusat() && ! $user->isPimpinanPusat()) {
            abort(403, 'Peran akun Anda tidak memiliki izin untuk melihat dokumentasi kegiatan.');
        }

        if (blank($kegiatan->foto_dokumentasi)) {
            abort(404, 'Kegiatan ini tidak memiliki dokumentasi foto historis.');
        }

        $path = (string) $kegiatan->foto_dokumentasi;

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->response($path);
        }

        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->response($path);
        }

        abort(404, 'Berkas foto dokumentasi historis tidak ditemukan pada penyimpanan server.');
    }
}

