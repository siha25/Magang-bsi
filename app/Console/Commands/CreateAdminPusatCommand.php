<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminPusatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin-pusat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat akun Administrator Pusat (Admin Pusat) pertama secara interaktif dan aman';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('========================================================');
        $this->info('  Pembuatan Akun Admin Pusat Pertama — SIHA Yayasan     ');
        $this->info('========================================================');

        $name = trim((string) $this->ask('Masukkan Nama Lengkap Admin Pusat'));
        while (blank($name)) {
            $this->error('Nama lengkap tidak boleh kosong.');
            $name = trim((string) $this->ask('Masukkan Nama Lengkap Admin Pusat'));
        }

        $email = trim((string) $this->ask('Masukkan Alamat Email Admin Pusat'));
        $validator = Validator::make(['email' => $email], [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
        ]);

        while ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            $email = trim((string) $this->ask('Masukkan Alamat Email Admin Pusat'));
            $validator = Validator::make(['email' => $email], [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            ]);
        }

        $password = (string) $this->secret('Masukkan Password (karakter tidak akan ditampilkan di layar)');
        while (strlen($password) < 8) {
            $this->error('Password minimal 8 karakter demi keamanan akun.');
            $password = (string) $this->secret('Masukkan Password (karakter tidak akan ditampilkan di layar)');
        }

        $passwordConfirmation = (string) $this->secret('Konfirmasi Password');
        while ($password !== $passwordConfirmation) {
            $this->error('Konfirmasi password tidak cocok.');
            $password = (string) $this->secret('Masukkan Password Baru');
            while (strlen($password) < 8) {
                $this->error('Password minimal 8 karakter.');
                $password = (string) $this->secret('Masukkan Password Baru');
            }
            $passwordConfirmation = (string) $this->secret('Konfirmasi Password');
        }

        if (! $this->confirm("Buat akun Admin Pusat dengan email [{$email}]?", true)) {
            $this->warn('Pembuatan akun dibatalkan.');

            return self::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => User::ROLE_ADMIN_PUSAT,
            'asrama_id' => null,
            'status_aktif' => true,
        ]);

        $this->newLine();
        $this->info('✔ Akun Admin Pusat berhasil dibuat dengan aman!');
        $this->table(
            ['Nama', 'Email', 'Role', 'Status'],
            [[$user->name, $user->email, 'Admin Pusat', 'Aktif']]
        );

        return self::SUCCESS;
    }
}
