<?php

namespace Tests\Feature;

use Tests\TestCase;

class HeaderTest extends TestCase
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
     * Memastikan header pada halaman dashboard menampilkan judul yang tepat,
     * profil ringkas pengguna, dan tidak memuat elemen dummy/lama.
     */
    public function test_dashboard_header_is_minimalist_and_clean(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Ambil elemen <header>...</header> saja
        $content = $response->getContent();
        preg_match('/<header.*?<\/header>/s', $content, $matches);
        $this->assertNotEmpty($matches, 'Tag <header> harus ditemukan pada halaman.');
        $headerHtml = $matches[0];

        // 1. Memastikan judul aktif adalah Dashboard
        $this->assertStringContainsString('Dashboard', $headerHtml);

        // 2. Memastikan elemen ringkas profil (avatar, nama singkat, peran)
        $this->assertStringContainsString('Admin', $headerHtml);
        $this->assertStringContainsString('Pengurus', $headerHtml);

        // 3. Memastikan elemen yang diminta dihapus TIDAK ada di header
        $this->assertStringNotContainsString('Yayasan Rumah Harapan', $headerHtml);
        $this->assertStringNotContainsString('Divisi Pendidikan Anak Asuh', $headerHtml);
        $this->assertStringNotContainsString('Oktober 2026', $headerHtml);
        $this->assertStringNotContainsString('Semester Ganjil', $headerHtml);
        $this->assertStringNotContainsString('Cari anak, cabang, hafalan...', $headerHtml);
        $this->assertStringNotContainsString('Ctrl K', $headerHtml);
        $this->assertStringNotContainsString('notif-menu', $headerHtml);
        $this->assertStringNotContainsString('profile-menu', $headerHtml);
        $this->assertStringNotContainsString('Keluar dari Sistem', $headerHtml);
        $this->assertStringNotContainsString('Aktif Online', $headerHtml);
        $this->assertStringNotContainsString('close', strtolower($headerHtml));
    }

    /**
     * Memastikan header pada halaman Data Asrama menampilkan judul Data Asrama.
     */
    public function test_asrama_header_displays_correct_dynamic_title(): void
    {
        $response = $this->get('/asrama');

        $response->assertStatus(200);

        $content = $response->getContent();
        preg_match('/<header.*?<\/header>/s', $content, $matches);
        $this->assertNotEmpty($matches);
        $headerHtml = $matches[0];

        $this->assertStringContainsString('Data Asrama', $headerHtml);
        $this->assertStringContainsString('Admin', $headerHtml);
        $this->assertStringContainsString('Pengurus', $headerHtml);
    }

    /**
     * Memastikan header pada halaman Data Anak Asuh menampilkan judul Data Anak Asuh.
     */
    public function test_anak_asuh_header_displays_correct_dynamic_title(): void
    {
        $response = $this->get('/anak-asuh');

        $response->assertStatus(200);

        $content = $response->getContent();
        preg_match('/<header.*?<\/header>/s', $content, $matches);
        $this->assertNotEmpty($matches);
        $headerHtml = $matches[0];

        $this->assertStringContainsString('Data Anak Asuh', $headerHtml);
        $this->assertStringContainsString('Admin', $headerHtml);
        $this->assertStringContainsString('Pengurus', $headerHtml);
    }
}
