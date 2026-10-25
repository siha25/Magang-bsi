<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Seeder khusus pengembangan lokal.
        if (! app()->environment('local')) {
            throw new RuntimeException(
                'Seeder hanya boleh dijalankan di lingkungan lokal.'
            );
        }

        $email = 'admin.dev@simpena.test';

        // Jangan mengubah akun yang sudah terdaftar.
        if (User::where('email', $email)->exists()) {
            $this->command?->warn(
                'Akun Admin Pusat Developer sudah tersedia.'
            );

            return;
        }

        // Buat akun Admin Pusat tambahan.
        $user = User::create([
            'name' => 'Admin Pusat Developer',
            'email' => $email,
            'password' => Hash::make('SimpenaDev2026!'),
            'role' => User::ROLE_ADMIN_PUSAT,
            'status_aktif' => true,
            'asrama_id' => null,
        ]);

        // Verifikasi email tanpa mengubah penanda admin utama.
        $user->email_verified_at = now();
        $user->save();

        $this->command?->info(
            'Akun Admin Pusat Developer berhasil dibuat.'
        );
    }
}
