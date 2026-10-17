<?php

namespace Tests\Feature;

use App\Models\Cabang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CabangTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Memastikan halaman daftar cabang menampilkan kondisi kosong ketika belum ada data.
     */
    public function test_cabang_index_displays_empty_state_when_no_data(): void
    {
        $response = $this->get(route('cabang.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cabang.index');
        $response->assertSee('Manajemen Data Cabang Asrama');
        $response->assertSee('Belum Ada Data Cabang');
    }

    /**
     * Memastikan formulir tambah cabang dapat diakses.
     */
    public function test_cabang_create_page_renders_properly(): void
    {
        $response = $this->get(route('cabang.create'));

        $response->assertStatus(200);
        $response->assertViewIs('cabang.create');
        $response->assertSee('Tambah Cabang Asrama Baru');
        $response->assertSee('Kode Cabang');
        $response->assertSee('Nama Cabang Asrama');
    }

    /**
     * Memastikan pengguna dapat menyimpan cabang baru.
     */
    public function test_can_store_new_cabang_successfully(): void
    {
        $payload = [
            'kode_cabang' => 'CBG-BDG-01',
            'nama_cabang' => 'Cabang Bandung Pusat',
            'wilayah' => 'Bandung, Jawa Barat',
            'penanggung_jawab' => 'Ust. H. Ridwan Kamil',
            'nomor_telepon' => '0812-2345-6789',
            'alamat' => 'Jl. Cisangkuy No. 42, Bandung',
            'status_aktif' => '1',
        ];

        $response = $this->post(route('cabang.store'), $payload);

        $response->assertRedirect(route('cabang.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cabang', [
            'kode_cabang' => 'CBG-BDG-01',
            'nama_cabang' => 'Cabang Bandung Pusat',
            'status_aktif' => true,
        ]);
    }

    /**
     * Memastikan validasi berjalan jika kolom wajib kosong atau kode cabang duplikat.
     */
    public function test_validates_required_and_unique_fields(): void
    {
        Cabang::create([
            'kode_cabang' => 'CBG-BGR-01',
            'nama_cabang' => 'Cabang Bogor Utama',
        ]);

        $response = $this->post(route('cabang.store'), [
            'kode_cabang' => 'CBG-BGR-01', // duplikat
            'nama_cabang' => '', // kosong
        ]);

        $response->assertSessionHasErrors(['kode_cabang', 'nama_cabang']);
    }

    /**
     * Memastikan halaman edit cabang dapat diakses dengan nilai awal.
     */
    public function test_cabang_edit_page_renders_with_existing_data(): void
    {
        $cabang = Cabang::create([
            'kode_cabang' => 'CBG-DPK-01',
            'nama_cabang' => 'Cabang Depok Indah',
            'wilayah' => 'Depok',
        ]);

        $response = $this->get(route('cabang.edit', $cabang));

        $response->assertStatus(200);
        $response->assertViewIs('cabang.edit');
        $response->assertSee('CBG-DPK-01');
        $response->assertSee('Cabang Depok Indah');
    }

    /**
     * Memastikan data cabang berhasil diperbarui.
     */
    public function test_can_update_existing_cabang(): void
    {
        $cabang = Cabang::create([
            'kode_cabang' => 'CBG-YGY-01',
            'nama_cabang' => 'Cabang Yogyakarta Lama',
            'status_aktif' => true,
        ]);

        $response = $this->put(route('cabang.update', $cabang), [
            'kode_cabang' => 'CBG-YGY-01', // tetap sama
            'nama_cabang' => 'Cabang Yogyakarta Baru',
            'status_aktif' => '1',
        ]);

        $response->assertRedirect(route('cabang.index'));
        $this->assertDatabaseHas('cabang', [
            'id' => $cabang->id,
            'nama_cabang' => 'Cabang Yogyakarta Baru',
        ]);
    }

    /**
     * Memastikan status cabang dapat diubah aktif / nonaktif.
     */
    public function test_can_toggle_cabang_status(): void
    {
        $cabang = Cabang::create([
            'kode_cabang' => 'CBG-SBY-01',
            'nama_cabang' => 'Cabang Surabaya',
            'status_aktif' => true,
        ]);

        $response = $this->patch(route('cabang.toggle-status', $cabang));

        $response->assertRedirect();
        $this->assertDatabaseHas('cabang', [
            'id' => $cabang->id,
            'status_aktif' => false,
        ]);

        // Toggle kembali menjadi aktif
        $this->patch(route('cabang.toggle-status', $cabang));
        $this->assertDatabaseHas('cabang', [
            'id' => $cabang->id,
            'status_aktif' => true,
        ]);
    }

    /**
     * Memastikan fitur pencarian berdasarkan kode atau nama berfungsi.
     */
    public function test_search_cabang_by_keyword(): void
    {
        Cabang::create(['kode_cabang' => 'CBG-JKT-01', 'nama_cabang' => 'Cabang Jakarta']);
        Cabang::create(['kode_cabang' => 'CBG-MDN-01', 'nama_cabang' => 'Cabang Medan']);

        $response = $this->get(route('cabang.index', ['cari' => 'Jakarta']));

        $response->assertStatus(200);
        $response->assertSee('Cabang Jakarta');
        $response->assertDontSee('Cabang Medan');
    }

    /**
     * Memastikan pagination bekerja jika data cabang melebihi 30 cabang.
     */
    public function test_pagination_supports_more_than_thirty_cabang(): void
    {
        for ($i = 1; $i <= 35; $i++) {
            Cabang::create([
                'kode_cabang' => sprintf('CBG-%03d', $i),
                'nama_cabang' => "Cabang Test Ke-{$i}",
                'status_aktif' => true,
            ]);
        }

        $this->assertEquals(35, Cabang::count());

        $response = $this->get(route('cabang.index'));
        $response->assertStatus(200);

        // Halaman 1 memuat 10 item (sesuai pagination per page 10)
        $this->assertCount(10, $response->viewData('cabangs'));

        // Cek halaman 4
        $responsePage4 = $this->get(route('cabang.index', ['page' => 4]));
        $responsePage4->assertStatus(200);
        $this->assertCount(5, $responsePage4->viewData('cabangs'));
    }
}
