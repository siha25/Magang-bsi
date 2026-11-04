<?php

// BAGIAN: PENGURUS PUSAT - Service Kelola Akun & Entitas Cabang

namespace App\Pusat\Services;

use App\Models\Asrama;
use App\Models\User;
use App\Services\AsramaCodeGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AkunCabangService
{
    public function __construct(
        protected AsramaCodeGenerator $codeGenerator
    ) {}

    /**
     * Membuat cabang baru dan akun login pengurus cabang secara atomik.
     *
     * @param  array{nama_cabang: string, wilayah: string, penanggung_jawab: string, email: string, password: string, nomor_telepon?: ?string, alamat?: ?string}  $data
     * @return array{cabang: Asrama, user: User}
     */
    public function createCabangDanAkun(array $data): array
    {
        return DB::transaction(function () use ($data): array {
            // 1. Buat data Cabang / Asrama
            $asrama = new Asrama([
                'nama_asrama' => $data['nama_cabang'],
                'wilayah' => $data['wilayah'],
                'penanggung_jawab' => $data['penanggung_jawab'],
                'nomor_telepon' => $data['nomor_telepon'] ?? null,
                'alamat' => $data['alamat'] ?? null,
                'status_aktif' => true,
            ]);
            $asrama->kode_asrama = $this->codeGenerator->next();
            $asrama->save();

            // 2. Buat akun login role cabang yang terikat ke cabang ini
            $user = new User([
                'name' => $data['penanggung_jawab'],
                'email' => strtolower(trim($data['email'])),
                'role' => User::ROLE_ADMIN_CABANG,
                'asrama_id' => $asrama->id,
                'status_aktif' => true,
            ]);
            // Password disimpan selalu dalam bentuk hash, tidak boleh plaintext
            $user->password = Hash::make($data['password']);
            $user->encrypted_password = $data['password'];
            $user->email_verified_at = now();
            $user->save();

            return [
                'cabang' => $asrama,
                'user' => $user,
            ];
        });
    }

    /**
     * Memperbarui data cabang dan akun pengurus terkait.
     */
    public function updateCabangDanAkun(Asrama $asrama, array $data): Asrama
    {
        return DB::transaction(function () use ($asrama, $data): Asrama {
            $asrama->update([
                'nama_asrama' => $data['nama_cabang'],
                'wilayah' => $data['wilayah'],
                'penanggung_jawab' => $data['penanggung_jawab'],
                'nomor_telepon' => $data['nomor_telepon'] ?? $asrama->nomor_telepon,
                'alamat' => $data['alamat'] ?? $asrama->alamat,
            ]);

            // Perbarui juga data pengurus pada akun login
            $user = $asrama->users()->first();
            if ($user) {
                $userUpdate = ['name' => $data['penanggung_jawab']];
                if (! empty($data['email'])) {
                    $userUpdate['email'] = strtolower(trim($data['email']));
                }
                $user->update($userUpdate);
            }

            return $asrama;
        });
    }

    /**
     * Reset password akun pengurus cabang (disimpan dalam bentuk hash).
     */
    public function resetPasswordCabang(Asrama $asrama, string $newPassword): void
    {
        DB::transaction(function () use ($asrama, $newPassword): void {
            $users = $asrama->users()->get();
            foreach ($users as $user) {
                $user->password = Hash::make($newPassword);
                $user->encrypted_password = $newPassword;
                $user->save();
            }
        });
    }

    /**
     * Mengaktifkan atau menonaktifkan status cabang dan seluruh akun loginnya.
     */
    public function toggleStatusCabang(Asrama $asrama): bool
    {
        return DB::transaction(function () use ($asrama): bool {
            $newStatus = ! $asrama->status_aktif;
            $asrama->status_aktif = $newStatus;
            $asrama->save();

            // Selaraskan status aktif akun pengurus cabang
            $asrama->users()->update(['status_aktif' => $newStatus]);

            return $newStatus;
        });
    }
}
