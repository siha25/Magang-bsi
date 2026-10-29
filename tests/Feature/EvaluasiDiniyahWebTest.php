<?php

namespace Tests\Feature;

use App\Models\AnakAsuh;
use App\Models\Asrama;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EvaluasiDiniyahWebTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_cabang_can_access_evaluasi_diniyah_index(): void
    {
        $asrama = Asrama::forceCreate([
            'kode_asrama' => 'ASR-01',
            'nama_asrama' => 'Asrama Surabaya',
            'wilayah' => 'Jawa Timur',
            'status_aktif' => true,
        ]);

        $adminCabang = User::factory()->create([
            'role' => User::ROLE_ADMIN_CABANG,
            'asrama_id' => $asrama->id,
            'status_aktif' => true,
        ]);

        $response = $this->actingAs($adminCabang)->get(route('evaluasi-diniyah.index'));

        $response->assertOk();
        $response->assertSee('Evaluasi & Nilai Diniyah Santri', false);
        $response->assertSee('Input Nilai Ulangan');
    }

    public function test_admin_cabang_can_input_evaluation_grade_for_santri(): void
    {
        $asrama = Asrama::forceCreate([
            'kode_asrama' => 'ASR-01',
            'nama_asrama' => 'Asrama Surabaya',
            'wilayah' => 'Jawa Timur',
            'status_aktif' => true,
        ]);

        $santri = AnakAsuh::create([
            'asrama_id' => $asrama->id,
            'nomor_induk' => 'SAN-001',
            'nama_lengkap' => 'Muhammad Fatih',
            'jenis_kelamin' => 'L',
            'jenjang_pendidikan' => 'SMP',
            'kelas' => '7',
            'status_aktif' => true,
        ]);

        $adminCabang = User::factory()->create([
            'role' => User::ROLE_ADMIN_CABANG,
            'asrama_id' => $asrama->id,
            'status_aktif' => true,
        ]);

        $postData = [
            'asrama_id' => $asrama->id,
            'anak_asuh_id' => $santri->id,
            'jenis_evaluasi' => "Hafalan Al-Qur'an",
            'materi' => 'Ujian Hafalan Surat Al-Mulk',
            'tanggal_evaluasi' => '2026-10-29',
            'nilai' => 95,
            'catatan_pengaju' => 'Bacaan fasih dan lancar dengan tajwid sangat baik.',
        ];

        $response = $this->actingAs($adminCabang)->post(route('evaluasi-diniyah.store'), $postData);

        $response->assertRedirect(route('evaluasi-diniyah.index'));
        $this->assertDatabaseHas('evaluasi_diniyah', [
            'asrama_id' => $asrama->id,
            'anak_asuh_id' => $santri->id,
            'materi' => 'Ujian Hafalan Surat Al-Mulk',
            'nilai' => 95,
            'predikat' => 'Mumtaz (A)',
        ]);
    }

    public function test_validation_rejects_out_of_range_score(): void
    {
        $asrama = Asrama::forceCreate([
            'kode_asrama' => 'ASR-01',
            'nama_asrama' => 'Asrama Surabaya',
            'status_aktif' => true,
        ]);

        $santri = AnakAsuh::create([
            'asrama_id' => $asrama->id,
            'nomor_induk' => 'SAN-002',
            'nama_lengkap' => 'Ahmad Bilal',
            'jenis_kelamin' => 'L',
            'status_aktif' => true,
        ]);

        $adminCabang = User::factory()->create([
            'role' => User::ROLE_ADMIN_CABANG,
            'asrama_id' => $asrama->id,
            'status_aktif' => true,
        ]);

        $postData = [
            'asrama_id' => $asrama->id,
            'anak_asuh_id' => $santri->id,
            'jenis_evaluasi' => 'Fiqih & Ibadah',
            'materi' => 'Ujian Bab Thaharah',
            'tanggal_evaluasi' => '2026-10-29',
            'nilai' => 150, // Melebihi 100
        ];

        $response = $this->actingAs($adminCabang)->post(route('evaluasi-diniyah.store'), $postData);

        $response->assertSessionHasErrors('nilai');
        $this->assertDatabaseCount('evaluasi_diniyah', 0);
    }
}
