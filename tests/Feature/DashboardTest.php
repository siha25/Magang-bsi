<?php

namespace Tests\Feature;

use App\Models\AnakAsuh;
use App\Models\Asrama;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $testUser = User::factory()->create([
            'name' => 'Admin Pengurus',
            'email' => 'admin_test@example.com',
            'role' => User::ROLE_ADMIN_PUSAT,
            'status_aktif' => true,
        ]);
        $this->actingAs($testUser);
    }

    /**
     * Memastikan halaman dashboard dapat diakses dan menampilkan komponen utama.
     */
    public function test_dashboard_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertSee('Pendidikan Ansuh');
        $response->assertSee('Yayasan Rumah Harapan');
        $response->assertSee('Admin Pengurus');
    }

    /**
     * Memastikan menu navigasi sidebar utama tersedia pada tampilan.
     */
    public function test_sidebar_menus_are_rendered(): void
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Data Asrama');
        $response->assertSee('Data Anak Asuh');
        $response->assertSee('Evaluasi Diniyah');
        $response->assertSee("Hafalan Al-Qur'an", false);
        $response->assertSee('Hafalan Hadis');
        $response->assertSee('Pengetahuan Diniyah');
        $response->assertSee('Kesehatan Anak');
        $response->assertSee('Kasus & Pembinaan', false);
        $response->assertSee('Laporan');
        $response->assertSee('Pengaturan');
    }

    /**
     * Memastikan statistik utama dan tabel asrama menggunakan data nyata database serta istilah Asrama.
     */
    public function test_statistics_and_asrama_table_use_real_database_data(): void
    {
        $totalAsrama = Asrama::count();
        $totalAnak = AnakAsuh::count();

        $response = $this->get('/');

        $response->assertStatus(200);

        // Memastikan istilah 'Total Asrama' digunakan, bukan 'Total Cabang'
        $response->assertSee('Total Asrama');
        $response->assertDontSee('Total Cabang');

        // Memastikan angka statistik sesuai database
        $response->assertSee((string) $totalAsrama);
        $response->assertSee((string) $totalAnak);

        // Memastikan tabel ringkasan asrama menggunakan istilah Asrama
        $response->assertSee('Tabel Ringkasan Asrama Yayasan Rumah Harapan');
        $response->assertDontSee('Tabel Ringkasan Cabang');

        // Memastikan tidak ada klaim palsu dan angka dummy evaluasi
        $response->assertDontSee('Sinkronisasi Normal');
        $response->assertDontSee('secara real-time');
        $response->assertDontSee('91.9%');
        $response->assertDontSee('78.2%');

        // Memastikan status 'Belum tersedia' muncul secara informatif
        $response->assertSee('Belum tersedia');
    }
}
