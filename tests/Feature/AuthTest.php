<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'laravel',
            'database.connections.mysql.host' => '127.0.0.1',
            'database.connections.mysql.port' => env('DB_PORT', '3306'),
            'database.connections.mysql.username' => env('DB_USERNAME', 'root'),
            'database.connections.mysql.password' => env('DB_PASSWORD', ''),
        ]);
    }

    /**
     * Memastikan halaman login dapat dimuat dengan baik.
     */
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Pendidikan Ansuh');
        $response->assertSee('Yayasan Rumah Harapan');
        $response->assertSee('Masuk Akun Pengurus');
        $response->assertSee('Masuk ke Sistem');
    }

    /**
     * Memastikan guest yang mengakses route operasional diarahkan ke halaman login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $responseDashboard = $this->get('/');
        $responseDashboard->assertRedirect('/login');

        $responseAsrama = $this->get('/asrama');
        $responseAsrama->assertRedirect('/login');

        $responseAnakAsuh = $this->get('/anak-asuh');
        $responseAnakAsuh->assertRedirect('/login');
    }

    /**
     * Memastikan login berhasil dengan kredensial yang benar dan session diregenerasi.
     */
    public function test_user_can_login_with_correct_credentials(): void
    {
        $email = 'test_login_user_'.uniqid().'@example.com';
        $user = User::create([
            'name' => 'Pengurus Uji Coba',
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ADMIN_PUSAT,
            'status_aktif' => true,
        ]);

        try {
            $response = $this->post('/login', [
                'email' => $email,
                'password' => 'password123',
            ]);

            $response->assertRedirect(route('dashboard'));
            $this->assertAuthenticatedAs($user);
        } finally {
            $user->delete();
        }
    }

    /**
     * Memastikan percobaan login dengan password salah ditolak.
     */
    public function test_user_cannot_login_with_incorrect_password(): void
    {
        $email = 'test_wrong_pwd_'.uniqid().'@example.com';
        $user = User::create([
            'name' => 'Pengurus Password Salah',
            'email' => $email,
            'password' => Hash::make('password_benar'),
            'role' => User::ROLE_ADMIN_PUSAT,
            'status_aktif' => true,
        ]);

        try {
            $response = $this->post('/login', [
                'email' => $email,
                'password' => 'password_keliru',
            ]);

            $response->assertSessionHasErrors('email');
            $this->assertGuest();
        } finally {
            $user->delete();
        }
    }

    /**
     * Memastikan akun yang berstatus nonaktif (status_aktif = false) ditolak saat login.
     */
    public function test_inactive_user_cannot_login(): void
    {
        $email = 'test_inactive_'.uniqid().'@example.com';
        $user = User::create([
            'name' => 'Pengurus Nonaktif',
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ADMIN_PUSAT,
            'status_aktif' => false,
        ]);

        try {
            $response = $this->post('/login', [
                'email' => $email,
                'password' => 'password123',
            ]);

            $response->assertSessionHasErrors('email');
            $this->assertGuest();
        } finally {
            $user->delete();
        }
    }

    /**
     * Memastikan logout berhasil dan session diinvalidasi.
     */
    public function test_user_can_logout_successfully(): void
    {
        $email = 'test_logout_'.uniqid().'@example.com';
        $user = User::create([
            'name' => 'Pengurus Logout',
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ADMIN_PUSAT,
            'status_aktif' => true,
        ]);

        try {
            $response = $this->actingAs($user)->post('/logout');

            $response->assertRedirect('/login');
            $this->assertGuest();
        } finally {
            $user->delete();
        }
    }
}
