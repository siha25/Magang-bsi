<?php

namespace Tests\Feature;

use App\Models\Asrama;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function makeAsrama(string $code, string $name): Asrama
    {
        $asrama = new Asrama;
        $asrama->forceFill([
            'kode_asrama' => $code,
            'nama_asrama' => $name,
            'status_aktif' => true,
        ])->save();

        return $asrama;
    }

    protected function makeUser(array $overrides = []): User
    {
        $defaults = [
            'name' => 'Pengguna Uji',
            'email' => 'user-'.uniqid().'@example.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ADMIN_PUSAT,
            'status_aktif' => true,
            'asrama_id' => null,
        ];

        return User::create(array_merge($defaults, $overrides));
    }

    public function test_guest_cannot_access_protected_pages(): void
    {
        $this->get('/')->assertRedirect('/login');
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/asrama')->assertRedirect('/login');
        $this->get('/anak-asuh')->assertRedirect('/login');
        $this->get('/kegiatan-harian')->assertRedirect('/login');
    }

    public function test_inactive_user_is_rejected_by_active_middleware(): void
    {
        $user = $this->makeUser(['status_aktif' => false]);

        $this->actingAs($user)->get('/dashboard')->assertForbidden();
    }

    public function test_admin_pusat_can_access_management_pages(): void
    {
        $user = $this->makeUser(['role' => User::ROLE_ADMIN_PUSAT]);

        $this->actingAs($user)->get('/dashboard')->assertOk();
        $this->actingAs($user)->get('/asrama')->assertOk();
        $this->actingAs($user)->get('/anak-asuh')->assertOk();
    }

    public function test_admin_cabang_cannot_access_other_asrama_or_modify_it(): void
    {
        $asramaA = $this->makeAsrama('A01_'.uniqid(), 'Asrama A');
        $asramaB = $this->makeAsrama('B01_'.uniqid(), 'Asrama B');

        $user = $this->makeUser([
            'role' => User::ROLE_ADMIN_CABANG,
            'asrama_id' => $asramaA->id,
        ]);

        $this->actingAs($user)->get('/asrama')->assertForbidden();
        $this->actingAs($user)->get('/asrama/'.$asramaB->id.'/edit')->assertForbidden();
        $this->actingAs($user)->post('/asrama', [
            'kode_asrama' => 'C01',
            'nama_asrama' => 'Asrama C',
            'status_aktif' => true,
        ])->assertForbidden();
    }

    public function test_admin_cabang_cannot_submit_daily_activity_for_other_asrama(): void
    {
        $asramaA = $this->makeAsrama('D01_'.uniqid(), 'Asrama D');
        $asramaB = $this->makeAsrama('E01_'.uniqid(), 'Asrama E');

        $user = $this->makeUser([
            'role' => User::ROLE_ADMIN_CABANG,
            'asrama_id' => $asramaA->id,
        ]);

        $this->actingAs($user)->post('/kegiatan-harian', [
            'asrama_id' => $asramaB->id,
            'tanggal_kegiatan' => '2026-10-21',
            'kategori' => 'belajar',
            'nama_kegiatan' => 'Belajar Al-Qur\'an',
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:00',
            'jumlah_peserta' => 20,
        ])->assertForbidden();
    }

    public function test_pimpinan_pusat_is_read_only_and_cannot_make_changes(): void
    {
        $user = $this->makeUser(['role' => User::ROLE_PIMPINAN_PUSAT]);

        $this->actingAs($user)->get('/dashboard')->assertOk();
        $this->actingAs($user)->post('/asrama', [
            'kode_asrama' => 'F01',
            'nama_asrama' => 'Asrama F',
            'status_aktif' => true,
        ])->assertForbidden();
    }
}
