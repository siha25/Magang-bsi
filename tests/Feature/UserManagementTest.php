<?php

namespace Tests\Feature;

use App\Models\Asrama;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $adminPusat;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminPusat = User::factory()->create([
            'name' => 'Admin Pusat Utama',
            'email' => 'admin.pusat@simpena.test',
            'role' => User::ROLE_ADMIN_PUSAT,
            'status_aktif' => true,
        ]);
    }

    /**
     * Memastikan Admin Pusat dapat melihat halaman index manajemen pengguna.
     */
    public function test_admin_pusat_can_view_user_management_index(): void
    {
        $response = $this->actingAs($this->adminPusat)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
        $response->assertSee('Manajemen Akun Pengguna');
        $response->assertSee($this->adminPusat->email);
    }

    /**
     * Memastikan pengguna non-admin pusat tidak dapat mengakses halaman manajemen pengguna.
     */
    public function test_admin_cabang_cannot_access_user_management(): void
    {
        $asrama = new Asrama;
        $asrama->kode_asrama = 'ASR-001';
        $asrama->nama_asrama = 'Asrama Uji Coba';
        $asrama->status_aktif = true;
        $asrama->save();

        $adminCabang = User::factory()->create([
            'role' => User::ROLE_ADMIN_CABANG,
            'asrama_id' => $asrama->id,
            'status_aktif' => true,
        ]);

        $response = $this->actingAs($adminCabang)->get(route('users.index'));

        $response->assertStatus(403);
    }

    /**
     * Memastikan Admin Pusat dapat menambahkan akun baru dan password tersimpan secara aman.
     */
    public function test_admin_pusat_can_create_new_user_with_encrypted_password(): void
    {
        $asrama = new Asrama;
        $asrama->kode_asrama = 'ASR-002';
        $asrama->nama_asrama = 'Asrama Cabang Barat';
        $asrama->status_aktif = true;
        $asrama->save();

        $postData = [
            'name' => 'Ustadz Ahmad Cabang',
            'email' => 'ahmad.cabang@simpena.test',
            'role' => User::ROLE_ADMIN_CABANG,
            'asrama_id' => $asrama->id,
            'password' => 'RahasiaSantri2026!',
            'status_aktif' => '1',
        ];

        $response = $this->actingAs($this->adminPusat)
            ->post(route('users.store'), $postData);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'ahmad.cabang@simpena.test',
            'role' => User::ROLE_ADMIN_CABANG,
            'asrama_id' => $asrama->id,
        ]);

        $createdUser = User::where('email', 'ahmad.cabang@simpena.test')->first();
        $this->assertNotNull($createdUser);
        $this->assertSame('RahasiaSantri2026!', $createdUser->visible_password);
    }

    /**
     * Memastikan Admin Pusat dapat mereset password akun pengguna lain jika lupa password.
     */
    public function test_admin_pusat_can_reset_user_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Petugas Cabang',
            'email' => 'petugas@simpena.test',
            'role' => User::ROLE_ADMIN_CABANG,
            'status_aktif' => true,
        ]);

        $response = $this->actingAs($this->adminPusat)
            ->post(route('users.reset-password', $user), [
                'password' => 'PasswordBaru999!',
            ]);

        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertSame('PasswordBaru999!', $user->visible_password);
    }
}
