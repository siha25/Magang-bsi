<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardTest extends TestCase
{
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
        $response->assertSee('Ust. Ahmad Fauzi, S.Pd.I');
    }

    /**
     * Memastikan ke-11 menu sidebar tersedia pada tampilan.
     */
    public function test_all_eleven_sidebar_menus_are_rendered(): void
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('1. Dashboard');
        $response->assertSee('2. Data Cabang');
        $response->assertSee('3. Data Anak Asuh');
        $response->assertSee('4. Evaluasi Diniyah');
        $response->assertSee("5. Hafalan Al-Qur'an", false);
        $response->assertSee('6. Hafalan Hadis');
        $response->assertSee('7. Pengetahuan Diniyah');
        $response->assertSee('8. Kesehatan Anak');
        $response->assertSee('9. Kasus & Pembinaan', false);
        $response->assertSee('10. Laporan');
        $response->assertSee('11. Pengaturan');
    }

    /**
     * Memastikan kartu statistik utama dan data ringkasan cabang dirender dengan benar.
     */
    public function test_statistics_and_branches_table_are_displayed(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Statistik
        $response->assertSee('248'); // Total anak
        $response->assertSee('Total Cabang');
        $response->assertSee('SD: <strong class="text-emerald-700 text-base">82</strong>', false);
        $response->assertSee('SMP: <strong class="text-teal-700 text-base">96</strong>', false);
        $response->assertSee('SMA: <strong class="text-orange-600 text-base">70</strong>', false);
        $response->assertSee('91.9%');
        $response->assertSee('78.2%');
        $response->assertSee('20');

        // Tabel Cabang
        $response->assertSee('Cabang Bandung (Pusat)');
        $response->assertSee('Cabang Bogor');
        $response->assertSee('Cabang Depok');
        $response->assertSee('Cabang Sukabumi');
        $response->assertSee('Cabang Yogyakarta');
        $response->assertSee('Cabang Surabaya');
    }
}
