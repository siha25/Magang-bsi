<?php

namespace Tests\Feature;

use App\Models\AnakAsuh;
use App\Models\Asrama;
use App\Models\EvaluasiDiniyah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CabangDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function makeCabangUser(): array
    {
        $asrama = new Asrama;
        $asrama->forceFill([
            'kode_asrama' => 'ASR-'.uniqid(),
            'nama_asrama' => 'Asrama Cabang Test',
            'wilayah' => 'Jawa Barat',
            'status_aktif' => true,
        ])->save();

        $user = User::create([
            'name' => 'Pengurus Cabang Test',
            'email' => 'cabang_'.uniqid().'@example.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ADMIN_CABANG,
            'status_aktif' => true,
            'asrama_id' => $asrama->id,
        ]);

        return [$asrama, $user];
    }

    public function test_cabang_dashboard_displays_required_metrics_only(): void
    {
        [$asrama, $user] = $this->makeCabangUser();

        // Buat anak asuh aktif
        AnakAsuh::create([
            'asrama_id' => $asrama->id,
            'nomor_induk' => 'IND-'.uniqid(),
            'nama_lengkap' => 'Ahmad Santri',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2012-05-10',
            'jenjang_pendidikan' => 'SMP',
            'kelas' => '7',
            'tanggal_masuk' => '2025-01-01',
            'status_aktif' => true,
        ]);

        // Buat evaluasi hafalan
        EvaluasiDiniyah::create([
            'asrama_id' => $asrama->id,
            'anak_asuh_id' => 1,
            'jenis_evaluasi' => "Hafalan Al-Qur'an",
            'materi' => 'Surah An-Naba',
            'tanggal_evaluasi' => now()->toDateString(),
            'nilai' => 90,
            'predikat' => 'Mumtaz (A)',
            'status' => EvaluasiDiniyah::STATUS_DISETUJUI,
        ]);

        $response = $this->actingAs($user)->get(route('cabang.dashboard'));

        $response->assertOk();
        $response->assertViewIs('cabang.views.dashboard.index');

        // 1. Menampilkan jumlah anak asuh aktif
        $response->assertSee('Santri / Anak Asuh Aktif');
        $response->assertSee('1');

        // 2. Menampilkan jumlah sakit di bulan terkini
        $response->assertSee('Jumlah Sakit');
        $response->assertSee('Bulan Terkini');

        // 3. Menampilkan jumlah kasus anak di bulan terkini
        $response->assertSee('Jumlah Kasus Anak');

        // 4. Menampilkan grafik hasil ujian diniyah setiap bulannya
        $response->assertSee('Grafik Hasil Ujian Diniyah Setiap Bulannya');

        // 5. Menampilkan evaluasi hafalan
        $response->assertSee('Evaluasi Hafalan');
        $response->assertSee('Surah An-Naba');
        $response->assertSee('Mumtaz (A)');

        // Memastikan modul yang tidak diminta tidak ditampilkan di dashboard
        $response->assertDontSee('Kegiatan Bulan Ini');
        $response->assertDontSee('Kegiatan Harian Terbaru');
    }
}

