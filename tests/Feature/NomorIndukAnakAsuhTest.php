<?php

namespace Tests\Feature;

use App\Models\AnakAsuh;
use App\Models\Asrama;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NomorIndukAnakAsuhTest extends TestCase
{
    use DatabaseTransactions;

    private User $adminPusat;

    private Asrama $asrama;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminPusat = User::factory()->create([
            'role' => User::ROLE_ADMIN_PUSAT,
            'status_aktif' => true,
        ]);

        $this->asrama = Asrama::forceCreate([
            'nama_asrama' => 'Asrama Uji Penomoran',
            'kode_asrama' => 'ASR-888',
            'status_aktif' => true,
        ]);
    }

    public function test_preview_endpoint_authorization(): void
    {
        // 1. Guest tidak diizinkan
        $this->getJson(route('anak-asuh.preview-nomor-induk', [
            'asrama_id' => $this->asrama->id,
            'tanggal_masuk' => '2026-01-15',
        ]))->assertUnauthorized();

        // 2. Admin Cabang tidak diizinkan (403)
        $adminCabang = User::factory()->create([
            'role' => User::ROLE_ADMIN_CABANG,
            'status_aktif' => true,
            'asrama_id' => $this->asrama->id,
        ]);

        $this->actingAs($adminCabang)
            ->getJson(route('anak-asuh.preview-nomor-induk', [
                'asrama_id' => $this->asrama->id,
                'tanggal_masuk' => '2026-01-15',
            ]))->assertForbidden();

        // 3. Admin Pusat diizinkan (200)
        $this->actingAs($this->adminPusat)
            ->getJson(route('anak-asuh.preview-nomor-induk', [
                'asrama_id' => $this->asrama->id,
                'tanggal_masuk' => '2026-01-15',
            ]))->assertOk();
    }

    public function test_preview_endpoint_validation(): void
    {
        $this->actingAs($this->adminPusat);

        // Tanpa parameter
        $this->getJson(route('anak-asuh.preview-nomor-induk'))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['asrama_id', 'tanggal_masuk']);

        // Asrama tidak ada
        $this->getJson(route('anak-asuh.preview-nomor-induk', [
            'asrama_id' => 9999999,
            'tanggal_masuk' => '2026-01-15',
        ]))->assertStatus(422)
            ->assertJsonValidationErrors(['asrama_id']);
    }

    public function test_preview_endpoint_returns_expected_format(): void
    {
        $this->actingAs($this->adminPusat);

        $response = $this->getJson(route('anak-asuh.preview-nomor-induk', [
            'asrama_id' => $this->asrama->id,
            'tanggal_masuk' => '2026-05-10',
        ]));

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'nomor_induk' => '2688801',
                'nomor_urut' => 1,
                'tahun' => 2026,
                'yy' => '26',
                'kode_asrama_digits' => '888',
            ]);
    }

    public function test_store_generates_sequential_nomor_induk_and_resets_per_year(): void
    {
        $this->actingAs($this->adminPusat);

        // Anak ke-1 di tahun 2026
        $res1 = $this->post(route('anak-asuh.store'), [
            'asrama_id' => $this->asrama->id,
            'nama_lengkap' => 'Santri Satu',
            'jenis_kelamin' => 'L',
            'jenjang_pendidikan' => 'SD',
            'tanggal_masuk' => '2026-02-01',
            'status_aktif' => '1',
        ]);
        $res1->assertRedirect(route('anak-asuh.index'));

        $child1 = AnakAsuh::where('nama_lengkap', 'Santri Satu')->firstOrFail();
        $this->assertSame('2688801', $child1->nomor_induk);
        $this->assertSame(1, $child1->nomor_urut);

        // Anak ke-2 di tahun 2026 pada asrama yang sama -> urutan 02
        $res2 = $this->post(route('anak-asuh.store'), [
            'asrama_id' => $this->asrama->id,
            'nama_lengkap' => 'Santri Dua',
            'jenis_kelamin' => 'P',
            'jenjang_pendidikan' => 'SMP',
            'tanggal_masuk' => '2026-03-01',
            'status_aktif' => '1',
        ]);
        $res2->assertRedirect(route('anak-asuh.index'));

        $child2 = AnakAsuh::where('nama_lengkap', 'Santri Dua')->firstOrFail();
        $this->assertSame('2688802', $child2->nomor_induk);
        $this->assertSame(2, $child2->nomor_urut);

        // Anak ke-3 di tahun 2027 pada asrama yang sama -> reset urutan ke 01 dengan prefix 27
        $res3 = $this->post(route('anak-asuh.store'), [
            'asrama_id' => $this->asrama->id,
            'nama_lengkap' => 'Santri Tiga 2027',
            'jenis_kelamin' => 'L',
            'jenjang_pendidikan' => 'SMA',
            'tanggal_masuk' => '2027-01-10',
            'status_aktif' => '1',
        ]);
        $res3->assertRedirect(route('anak-asuh.index'));

        $child3 = AnakAsuh::where('nama_lengkap', 'Santri Tiga 2027')->firstOrFail();
        $this->assertSame('2788801', $child3->nomor_induk);
        $this->assertSame(1, $child3->nomor_urut);
    }

    public function test_store_uses_max_plus_one_not_count_when_record_deleted(): void
    {
        $this->actingAs($this->adminPusat);

        // Buat anak 1 (01)
        $this->post(route('anak-asuh.store'), [
            'asrama_id' => $this->asrama->id,
            'nama_lengkap' => 'Anak Urut 1',
            'jenis_kelamin' => 'L',
            'jenjang_pendidikan' => 'SD',
            'tanggal_masuk' => '2026-06-01',
        ]);

        // Buat anak 2 (02)
        $this->post(route('anak-asuh.store'), [
            'asrama_id' => $this->asrama->id,
            'nama_lengkap' => 'Anak Urut 2',
            'jenis_kelamin' => 'P',
            'jenjang_pendidikan' => 'SD',
            'tanggal_masuk' => '2026-06-02',
        ]);

        $child1 = AnakAsuh::where('nama_lengkap', 'Anak Urut 1')->firstOrFail();
        $this->assertSame('2688801', $child1->nomor_induk);

        // Hapus anak 1
        $child1->delete();

        // Buat anak 3: jika menggunakan count(), anak 3 akan dapat 02 (bentrok!). Dengan MAX() + 1, anak 3 wajib dapat 03!
        $this->post(route('anak-asuh.store'), [
            'asrama_id' => $this->asrama->id,
            'nama_lengkap' => 'Anak Urut 3',
            'jenis_kelamin' => 'L',
            'jenjang_pendidikan' => 'SD',
            'tanggal_masuk' => '2026-06-03',
        ]);

        $child3 = AnakAsuh::where('nama_lengkap', 'Anak Urut 3')->firstOrFail();
        $this->assertSame('2688803', $child3->nomor_induk);
        $this->assertSame(3, $child3->nomor_urut);
    }

    public function test_server_ignores_client_supplied_nomor_induk(): void
    {
        $this->actingAs($this->adminPusat);

        // Klien mengirim nomor induk manipulatif
        $this->post(route('anak-asuh.store'), [
            'asrama_id' => $this->asrama->id,
            'nomor_induk' => 'FAKE-999-TAMPERED',
            'nama_lengkap' => 'Anak Manipulasi',
            'jenis_kelamin' => 'L',
            'jenjang_pendidikan' => 'SD',
            'tanggal_masuk' => '2026-08-01',
        ]);

        $child = AnakAsuh::where('nama_lengkap', 'Anak Manipulasi')->firstOrFail();
        $this->assertSame('2688801', $child->nomor_induk);
        $this->assertNotSame('FAKE-999-TAMPERED', $child->nomor_induk);
    }

    public function test_update_does_not_modify_nomor_induk(): void
    {
        $this->actingAs($this->adminPusat);

        $this->post(route('anak-asuh.store'), [
            'asrama_id' => $this->asrama->id,
            'nama_lengkap' => 'Anak Asli',
            'jenis_kelamin' => 'L',
            'jenjang_pendidikan' => 'SD',
            'tanggal_masuk' => '2026-09-01',
        ]);

        $child = AnakAsuh::where('nama_lengkap', 'Anak Asli')->firstOrFail();
        $originalNomorInduk = $child->nomor_induk;

        // Coba kirim update dengan nomor induk berbeda
        $this->put(route('anak-asuh.update', $child), [
            'asrama_id' => $this->asrama->id,
            'nomor_induk' => '9999999',
            'nama_lengkap' => 'Anak Diperbarui',
            'jenis_kelamin' => 'L',
            'jenjang_pendidikan' => 'SMP',
            'tanggal_masuk' => '2026-09-01',
        ]);

        $child->refresh();
        $this->assertSame('Anak Diperbarui', $child->nama_lengkap);
        $this->assertSame($originalNomorInduk, $child->nomor_induk);
    }

    public function test_destroy_anak_asuh_success(): void
    {
        $this->actingAs($this->adminPusat);

        $this->post(route('anak-asuh.store'), [
            'asrama_id' => $this->asrama->id,
            'nama_lengkap' => 'Anak Untuk Dihapus',
            'jenis_kelamin' => 'P',
            'jenjang_pendidikan' => 'SD',
            'tanggal_masuk' => '2026-09-15',
        ]);

        $child = AnakAsuh::where('nama_lengkap', 'Anak Untuk Dihapus')->firstOrFail();

        $response = $this->delete(route('anak-asuh.destroy', $child));
        $response->assertRedirect(route('anak-asuh.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('anak_asuh', [
            'id' => $child->id,
        ]);
    }

    public function test_destroy_anak_asuh_forbidden_for_admin_cabang(): void
    {
        $adminCabang = User::factory()->create([
            'role' => User::ROLE_ADMIN_CABANG,
            'status_aktif' => true,
            'asrama_id' => $this->asrama->id,
        ]);

        $child = AnakAsuh::create([
            'asrama_id' => $this->asrama->id,
            'nomor_induk' => '2688899',
            'nomor_urut' => 99,
            'nama_lengkap' => 'Santri Cabang',
            'jenis_kelamin' => 'L',
            'jenjang_pendidikan' => 'SMP',
            'tanggal_masuk' => '2026-01-01',
            'status_aktif' => true,
        ]);

        $this->actingAs($adminCabang)
            ->delete(route('anak-asuh.destroy', $child))
            ->assertForbidden();

        $this->assertDatabaseHas('anak_asuh', [
            'id' => $child->id,
        ]);
    }
}
