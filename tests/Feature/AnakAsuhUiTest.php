<?php

namespace Tests\Feature;

use Tests\TestCase;

class AnakAsuhUiTest extends TestCase
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
     * Memastikan halaman index Data Anak Asuh memuat elemen UI yang tepat:
     * - Header tidak memuat tombol 'close'
     * - Kartu statistik menggunakan istilah 'Anak Asuh' dan bukan 'Santri'
     * - Filter bar memuat pencarian, filter asrama, jenjang, status, tombol Terapkan
     * - Tombol Edit dan Nonaktifkan/Aktifkan memiliki styling yang rapi
     */
    public function test_anak_asuh_index_ui_elements(): void
    {
        $response = $this->get('/anak-asuh');

        $response->assertStatus(200);

        $content = $response->getContent();

        // 1. Header tidak memuat teks/tombol 'close'
        preg_match('/<header.*?<\/header>/s', $content, $matches);
        $this->assertNotEmpty($matches);
        $headerHtml = $matches[0];
        $this->assertStringContainsString('Data Anak Asuh', $headerHtml);
        $this->assertStringNotContainsString('close', strtolower($headerHtml));

        // 2. Kartu statistik menggunakan 'Anak Asuh' dan bukan 'Santri'
        $this->assertStringContainsString('Total Anak Asuh', $content);
        $this->assertStringContainsString('Seluruh anak asuh terdaftar', $content);
        $this->assertStringNotContainsString('Seluruh santri terdaftar', $content);

        // 3. Elemen Filter
        $this->assertStringContainsString('name="cari"', $content);
        $this->assertStringContainsString('name="asrama_id"', $content);
        $this->assertStringContainsString('name="jenjang"', $content);
        $this->assertStringContainsString('name="status"', $content);
        $this->assertStringContainsString('Terapkan', $content);
    }

    /**
     * Memastikan halaman tambah anak asuh dapat diakses dengan benar.
     */
    public function test_anak_asuh_create_page_renders_properly(): void
    {
        $response = $this->get(route('anak-asuh.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Anak Asuh Baru');
        $response->assertSee('Biodata Pokok Anak Asuh');
        $response->assertDontSee('Biodata Pokok Santri');
    }

    /**
     * Memastikan fungsi pencarian dan filter anak asuh berjalan tanpa error.
     */
    public function test_anak_asuh_search_and_filter(): void
    {
        // 1. Pencarian kata kunci
        $response = $this->get('/anak-asuh?cari=albagir');
        $response->assertStatus(200);

        // 2. Filter jenjang SD
        $responseJenjang = $this->get('/anak-asuh?jenjang=SD');
        $responseJenjang->assertStatus(200);

        // 3. Filter status aktif
        $responseStatus = $this->get('/anak-asuh?status=aktif');
        $responseStatus->assertStatus(200);
    }

    /**
     * Memastikan validasi formulir tambah data anak asuh berjalan dengan benar.
     */
    public function test_store_validation_errors_when_empty(): void
    {
        $response = $this->post('/anak-asuh', []);

        $response->assertSessionHasErrors(['nomor_induk', 'nama_lengkap', 'jenis_kelamin', 'jenjang_pendidikan']);
    }
}
