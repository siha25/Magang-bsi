<?php

/**
 * SIPEPA — HafalanWorkflowTest
 *
 * Skenario pengujian alur hafalan Al-Qur'an dan Hadis:
 * - Input predikat A/B/C oleh Staff Cabang
 * - Status pending setelah input
 * - Predikat akhir null sebelum review
 * - Approve tanpa revisi (predikat akhir = predikat awal)
 * - Approve dengan revisi predikat akhir
 * - Predikat awal tidak tertimpa saat revisi
 * - Staff Cabang tidak dapat approve
 * - Staff Cabang tidak dapat akses asrama lain
 * - Manager (pimpinan_pusat) tidak dapat approve
 * - Data historis Hafalan Hadis (jenis_evaluasi = 'Hafalan Hadis') tetap terbaca
 * - Tidak terjadi pencampuran jenis evaluasi
 * - Perhitungan Mumtaz numerik (nilai >= 90) tetap benar untuk Nilai Diniyah
 *
 * PERINGATAN DATABASE:
 * Test ini menggunakan DatabaseTransactions (bukan RefreshDatabase) untuk rollback
 * setelah setiap test. Namun database yang dipakai SAMA dengan development
 * (pendidikan_ansuh) karena .env.testing tidak dikonfigurasi secara terpisah.
 * Migration kolom predikat_akhir BELUM dijalankan — test yang bergantung pada
 * kolom tersebut akan di-skip secara eksplisit.
 *
 * @see database/migrations/2026_11_12_070000_add_predikat_akhir_to_evaluasi_diniyah_table.php
 */

namespace Tests\Feature;

use App\Models\AnakAsuh;
use App\Models\Asrama;
use App\Models\EvaluasiDiniyah;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HafalanWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    private Asrama $asrama;

    private Asrama $asramaLain;

    private AnakAsuh $santri;

    private User $staffCabang;

    private User $adminPendik;

    private User $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->asrama = Asrama::forceCreate([
            'kode_asrama' => 'TST-HAF-01',
            'nama_asrama' => 'Asrama Test Hafalan',
            'wilayah' => 'Jawa Tengah',
            'status_aktif' => true,
        ]);

        $this->asramaLain = Asrama::forceCreate([
            'kode_asrama' => 'TST-HAF-02',
            'nama_asrama' => 'Asrama Test Lain',
            'wilayah' => 'Jawa Barat',
            'status_aktif' => true,
        ]);

        $this->santri = AnakAsuh::create([
            'asrama_id' => $this->asrama->id,
            'nomor_induk' => 'TST-HAF-SAN-001',
            'nama_lengkap' => 'Muhammad Faris Test',
            'jenis_kelamin' => 'L',
            'jenjang_pendidikan' => 'SMP',
            'kelas' => '7',
            'status_aktif' => true,
        ]);

        $this->staffCabang = User::factory()->create([
            'role' => User::ROLE_ADMIN_CABANG,
            'asrama_id' => $this->asrama->id,
            'status_aktif' => true,
        ]);

        $this->adminPendik = User::factory()->create([
            'role' => User::ROLE_ADMIN_PUSAT,
            'asrama_id' => null,
            'status_aktif' => true,
        ]);

        $this->manager = User::factory()->create([
            'role' => User::ROLE_PIMPINAN_PUSAT,
            'asrama_id' => null,
            'status_aktif' => true,
        ]);
    }

    // =========================================================================
    // BAGIAN 1: Input predikat A/B/C oleh Staff Cabang
    // =========================================================================

    public function test_staff_cabang_dapat_input_setoran_hafalan_quran_dengan_predikat_a(): void
    {
        $response = $this->actingAs($this->staffCabang)
            ->post(route('cabang.hafalan-quran.store'), [
                'anak_asuh_id' => $this->santri->id,
                'materi' => 'Surah Al-Fatihah',
                'tanggal_evaluasi' => '2026-11-01',
                'predikat' => 'A',
                'catatan_pengaju' => 'Bacaan sangat lancar dan fasih.',
            ]);

        $response->assertRedirect(route('cabang.hafalan-quran.index'));

        $this->assertDatabaseHas('evaluasi_diniyah', [
            'asrama_id' => $this->asrama->id,
            'anak_asuh_id' => $this->santri->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_QURAN,
            'materi' => 'Surah Al-Fatihah',
            'predikat' => 'A',
            'status' => EvaluasiDiniyah::STATUS_PENDING,
        ]);
    }

    public function test_staff_cabang_dapat_input_setoran_hafalan_quran_dengan_predikat_b_dan_c(): void
    {
        foreach (['B', 'C'] as $predikat) {
            $response = $this->actingAs($this->staffCabang)
                ->post(route('cabang.hafalan-quran.store'), [
                    'anak_asuh_id' => $this->santri->id,
                    'materi' => "Surah Al-Ikhlas — Predikat {$predikat}",
                    'tanggal_evaluasi' => '2026-11-01',
                    'predikat' => $predikat,
                    'catatan_pengaju' => null,
                ]);

            $response->assertRedirect(route('cabang.hafalan-quran.index'));

            $this->assertDatabaseHas('evaluasi_diniyah', [
                'materi' => "Surah Al-Ikhlas — Predikat {$predikat}",
                'predikat' => $predikat,
                'status' => EvaluasiDiniyah::STATUS_PENDING,
            ]);
        }
    }

    public function test_validasi_menolak_predikat_selain_a_b_c_untuk_hafalan_quran(): void
    {
        $response = $this->actingAs($this->staffCabang)
            ->post(route('cabang.hafalan-quran.store'), [
                'anak_asuh_id' => $this->santri->id,
                'materi' => 'Surah Al-Baqarah',
                'tanggal_evaluasi' => '2026-11-01',
                'predikat' => 'D', // tidak valid
            ]);

        $response->assertSessionHasErrors('predikat');
    }

    public function test_staff_cabang_dapat_input_setoran_hafalan_hadis_dengan_predikat_a(): void
    {
        $response = $this->actingAs($this->staffCabang)
            ->post(route('cabang.hafalan-hadist.store'), [
                'anak_asuh_id' => $this->santri->id,
                'materi' => 'Hadis Arbain no. 1 — Niat',
                'tanggal_evaluasi' => '2026-11-01',
                'predikat' => 'A',
                'catatan_pengaju' => 'Hafal dengan tartil.',
            ]);

        $response->assertRedirect(route('cabang.hafalan-hadist.index'));

        $this->assertDatabaseHas('evaluasi_diniyah', [
            'asrama_id' => $this->asrama->id,
            'anak_asuh_id' => $this->santri->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_HADIS,
            'materi' => 'Hadis Arbain no. 1 — Niat',
            'predikat' => 'A',
            'status' => EvaluasiDiniyah::STATUS_PENDING,
        ]);
    }

    // =========================================================================
    // BAGIAN 2: Status pending dan predikat akhir null sebelum review
    // =========================================================================

    public function test_setoran_hafalan_baru_memiliki_status_pending(): void
    {
        $evaluasi = EvaluasiDiniyah::create([
            'asrama_id' => $this->asrama->id,
            'anak_asuh_id' => $this->santri->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_QURAN,
            'materi' => 'Juz 30',
            'tanggal_evaluasi' => '2026-11-01',
            'predikat' => 'B',
            'status' => EvaluasiDiniyah::STATUS_PENDING,
            'diajukan_oleh' => $this->staffCabang->id,
            'diajukan_pada' => now(),
        ]);

        $this->assertSame(EvaluasiDiniyah::STATUS_PENDING, $evaluasi->fresh()->status);
        $this->assertTrue($evaluasi->fresh()->isPending());
    }

    public function test_predikat_akhir_null_sebelum_review(): void
    {
        // Lewati jika kolom predikat_akhir belum ada (migration belum dijalankan)
        if (! Schema::hasColumn('evaluasi_diniyah', 'predikat_akhir')) {
            $this->markTestSkipped('Kolom predikat_akhir belum ada. Jalankan migration 2026_11_12_070000 terlebih dahulu.');
        }

        $evaluasi = EvaluasiDiniyah::create([
            'asrama_id' => $this->asrama->id,
            'anak_asuh_id' => $this->santri->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_QURAN,
            'materi' => 'Surah Yasin',
            'tanggal_evaluasi' => '2026-11-01',
            'predikat' => 'A',
            'status' => EvaluasiDiniyah::STATUS_PENDING,
            'diajukan_oleh' => $this->staffCabang->id,
            'diajukan_pada' => now(),
        ]);

        $this->assertNull($evaluasi->fresh()->predikat_akhir);
    }

    // =========================================================================
    // BAGIAN 3: Approve tanpa revisi
    // =========================================================================

    public function test_admin_pendik_dapat_menyetujui_hafalan_tanpa_revisi(): void
    {
        if (! Schema::hasColumn('evaluasi_diniyah', 'predikat_akhir')) {
            $this->markTestSkipped('Kolom predikat_akhir belum ada.');
        }

        $evaluasi = EvaluasiDiniyah::create([
            'asrama_id' => $this->asrama->id,
            'anak_asuh_id' => $this->santri->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_QURAN,
            'materi' => 'Surah Al-Mulk',
            'tanggal_evaluasi' => '2026-11-01',
            'predikat' => 'B',
            'status' => EvaluasiDiniyah::STATUS_PENDING,
            'diajukan_oleh' => $this->staffCabang->id,
            'diajukan_pada' => now(),
        ]);

        $response = $this->actingAs($this->adminPendik)
            ->post(route('pusat.review-hafalan.approve', $evaluasi->id), [
                'catatan_evaluasi_pusat' => 'Hafalan diterima.',
            ]);

        $response->assertRedirect();

        $evaluasi->refresh();
        $this->assertSame(EvaluasiDiniyah::STATUS_DISETUJUI, $evaluasi->status);
        // Predikat akhir = predikat awal karena tidak ada revisi
        $this->assertSame('B', $evaluasi->predikat_akhir);
        // Predikat awal tidak berubah
        $this->assertSame('B', $evaluasi->predikat);
        $this->assertSame($this->adminPendik->id, $evaluasi->dinilai_oleh);
        $this->assertNotNull($evaluasi->dinilai_pada);
    }

    // =========================================================================
    // BAGIAN 4: Approve dengan revisi predikat akhir
    // =========================================================================

    public function test_admin_pendik_dapat_merevisi_predikat_akhir_menjadi_a(): void
    {
        if (! Schema::hasColumn('evaluasi_diniyah', 'predikat_akhir')) {
            $this->markTestSkipped('Kolom predikat_akhir belum ada.');
        }

        $evaluasi = EvaluasiDiniyah::create([
            'asrama_id' => $this->asrama->id,
            'anak_asuh_id' => $this->santri->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_QURAN,
            'materi' => 'Surah Al-Kahfi',
            'tanggal_evaluasi' => '2026-11-01',
            'predikat' => 'B', // predikat awal dari Staff Cabang
            'status' => EvaluasiDiniyah::STATUS_PENDING,
            'diajukan_oleh' => $this->staffCabang->id,
            'diajukan_pada' => now(),
        ]);

        $response = $this->actingAs($this->adminPendik)
            ->post(route('pusat.review-hafalan.approve-revisi', $evaluasi->id), [
                'predikat_akhir' => 'A',
                'catatan_evaluasi_pusat' => 'Setelah dievaluasi ulang, bacaan sangat lancar.',
            ]);

        $response->assertRedirect();

        $evaluasi->refresh();
        $this->assertSame(EvaluasiDiniyah::STATUS_DISETUJUI, $evaluasi->status);
        $this->assertSame('A', $evaluasi->predikat_akhir);
        // Predikat awal (B) TIDAK berubah
        $this->assertSame('B', $evaluasi->predikat);
    }

    // =========================================================================
    // BAGIAN 5: Staff Cabang tidak dapat approve
    // =========================================================================

    public function test_staff_cabang_tidak_dapat_mengapprove_hafalan(): void
    {
        if (! Schema::hasColumn('evaluasi_diniyah', 'predikat_akhir')) {
            $this->markTestSkipped('Kolom predikat_akhir belum ada.');
        }

        $evaluasi = EvaluasiDiniyah::create([
            'asrama_id' => $this->asrama->id,
            'anak_asuh_id' => $this->santri->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_QURAN,
            'materi' => 'Surah Al-Baqarah ayat 1-10',
            'tanggal_evaluasi' => '2026-11-01',
            'predikat' => 'A',
            'status' => EvaluasiDiniyah::STATUS_PENDING,
            'diajukan_oleh' => $this->staffCabang->id,
            'diajukan_pada' => now(),
        ]);

        $response = $this->actingAs($this->staffCabang)
            ->post(route('pusat.review-hafalan.approve', $evaluasi->id));

        // Staff cabang tidak memiliki akses ke route pusat
        $response->assertStatus(403);

        // Status tetap pending
        $this->assertSame(EvaluasiDiniyah::STATUS_PENDING, $evaluasi->fresh()->status);
    }

    // =========================================================================
    // BAGIAN 6: Staff Cabang tidak dapat mengakses asrama lain
    // =========================================================================

    public function test_staff_cabang_tidak_dapat_melihat_hafalan_asrama_lain(): void
    {
        $santriAsramaLain = AnakAsuh::create([
            'asrama_id' => $this->asramaLain->id,
            'nomor_induk' => 'TST-LAIN-001',
            'nama_lengkap' => 'Santri Asrama Lain',
            'jenis_kelamin' => 'P',
            'status_aktif' => true,
        ]);

        $evaluasiAsramaLain = EvaluasiDiniyah::create([
            'asrama_id' => $this->asramaLain->id,
            'anak_asuh_id' => $santriAsramaLain->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_QURAN,
            'materi' => 'Data Asrama Lain',
            'tanggal_evaluasi' => '2026-11-01',
            'predikat' => 'A',
            'status' => EvaluasiDiniyah::STATUS_PENDING,
            'diajukan_oleh' => $this->staffCabang->id,
            'diajukan_pada' => now(),
        ]);

        // Staff cabang asrama A mencoba melihat data asrama lain
        $response = $this->actingAs($this->staffCabang)
            ->get(route('cabang.hafalan-quran.show', $evaluasiAsramaLain->id));

        $response->assertStatus(403);
    }

    public function test_staff_cabang_tidak_dapat_menyimpan_hafalan_untuk_santri_asrama_lain(): void
    {
        $santriAsramaLain = AnakAsuh::create([
            'asrama_id' => $this->asramaLain->id,
            'nomor_induk' => 'TST-LAIN-002',
            'nama_lengkap' => 'Santri Lain 2',
            'jenis_kelamin' => 'L',
            'status_aktif' => true,
        ]);

        // Staff cabang A mencoba input hafalan untuk santri di asrama lain
        $response = $this->actingAs($this->staffCabang)
            ->post(route('cabang.hafalan-quran.store'), [
                'anak_asuh_id' => $santriAsramaLain->id,
                'materi' => 'Surah percobaan lintas asrama',
                'tanggal_evaluasi' => '2026-11-01',
                'predikat' => 'A',
            ]);

        // Harus gagal karena santri bukan milik cabang ini (404 dari firstOrFail)
        $response->assertStatus(404);
    }

    // =========================================================================
    // BAGIAN 7: Manager (pimpinan_pusat) tidak dapat approve
    // =========================================================================

    public function test_manager_pimpinan_pusat_tidak_dapat_mengapprove_hafalan(): void
    {
        if (! Schema::hasColumn('evaluasi_diniyah', 'predikat_akhir')) {
            $this->markTestSkipped('Kolom predikat_akhir belum ada.');
        }

        $evaluasi = EvaluasiDiniyah::create([
            'asrama_id' => $this->asrama->id,
            'anak_asuh_id' => $this->santri->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_HADIS,
            'materi' => 'Hadis Arbain no. 2',
            'tanggal_evaluasi' => '2026-11-01',
            'predikat' => 'B',
            'status' => EvaluasiDiniyah::STATUS_PENDING,
            'diajukan_oleh' => $this->staffCabang->id,
            'diajukan_pada' => now(),
        ]);

        // Manager (pimpinan_pusat) mencoba approve — harus ditolak oleh requireAdminPusat()
        $response = $this->actingAs($this->manager)
            ->post(route('pusat.review-hafalan.approve', $evaluasi->id));

        $response->assertStatus(403);

        // Status tetap pending
        $this->assertSame(EvaluasiDiniyah::STATUS_PENDING, $evaluasi->fresh()->status);
    }

    // =========================================================================
    // BAGIAN 8: Data historis Hafalan Hadis tetap terbaca
    // =========================================================================

    public function test_data_historis_hafalan_hadis_lama_tetap_terbaca(): void
    {
        // Simulasi data lama yang mungkin disimpan via DiniyahController lama
        // dengan jenis_evaluasi = 'Hafalan Hadis' (nilai numerik, status disetujui)
        $dataHistoris = EvaluasiDiniyah::create([
            'asrama_id' => $this->asrama->id,
            'anak_asuh_id' => $this->santri->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_HADIS,
            'materi' => 'Hadis Lama — Arbain no. 5',
            'tanggal_evaluasi' => '2026-10-01',
            'nilai' => 85.0,
            'predikat' => 'Jayyid Jiddan (B)', // predikat lama (numerik)
            'status' => EvaluasiDiniyah::STATUS_DISETUJUI,
            'diajukan_oleh' => $this->staffCabang->id,
            'diajukan_pada' => now()->subMonth(),
        ]);

        // Data harus bisa dibaca
        $found = EvaluasiDiniyah::where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_HADIS)
            ->where('id', $dataHistoris->id)
            ->first();

        $this->assertNotNull($found);
        $this->assertSame('Hafalan Hadis', $found->jenis_evaluasi);
        $this->assertSame('Hadis Lama — Arbain no. 5', $found->materi);
        $this->assertSame(EvaluasiDiniyah::STATUS_DISETUJUI, $found->status);
    }

    // =========================================================================
    // BAGIAN 9: Tidak terjadi pencampuran jenis evaluasi
    // =========================================================================

    public function test_query_hafalan_quran_tidak_mencampur_dengan_hafalan_hadis(): void
    {
        EvaluasiDiniyah::create([
            'asrama_id' => $this->asrama->id,
            'anak_asuh_id' => $this->santri->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_QURAN,
            'materi' => 'Surah Al-Fajr',
            'tanggal_evaluasi' => '2026-11-01',
            'predikat' => 'A',
            'status' => EvaluasiDiniyah::STATUS_PENDING,
            'diajukan_oleh' => $this->staffCabang->id,
            'diajukan_pada' => now(),
        ]);

        EvaluasiDiniyah::create([
            'asrama_id' => $this->asrama->id,
            'anak_asuh_id' => $this->santri->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_HADIS,
            'materi' => 'Hadis Arbain no. 3',
            'tanggal_evaluasi' => '2026-11-01',
            'predikat' => 'B',
            'status' => EvaluasiDiniyah::STATUS_PENDING,
            'diajukan_oleh' => $this->staffCabang->id,
            'diajukan_pada' => now(),
        ]);

        $hafalanQuran = EvaluasiDiniyah::where('asrama_id', $this->asrama->id)
            ->where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_QURAN)
            ->get();

        $hafalanHadis = EvaluasiDiniyah::where('asrama_id', $this->asrama->id)
            ->where('jenis_evaluasi', EvaluasiDiniyah::KATEGORI_HADIS)
            ->get();

        $this->assertCount(1, $hafalanQuran);
        $this->assertCount(1, $hafalanHadis);

        $this->assertSame('Surah Al-Fajr', $hafalanQuran->first()->materi);
        $this->assertSame('Hadis Arbain no. 3', $hafalanHadis->first()->materi);

        // Pastikan tidak ada campur aduk
        $this->assertNotSame(
            $hafalanQuran->first()->jenis_evaluasi,
            $hafalanHadis->first()->jenis_evaluasi
        );
    }

    // =========================================================================
    // BAGIAN 10: Perhitungan Mumtaz numerik untuk Nilai Diniyah
    // =========================================================================

    public function test_perhitungan_predikat_numerik_mumtaz_batas_90_tetap_benar(): void
    {
        // Nilai 90 tepat — harus Mumtaz (A)
        $this->assertSame('Mumtaz (A)', EvaluasiDiniyah::hitungPredikat(90.0));

        // Nilai 89.9 — bukan Mumtaz
        $this->assertSame('Jayyid Jiddan (B)', EvaluasiDiniyah::hitungPredikat(89.9));

        // Nilai 100 — Mumtaz
        $this->assertSame('Mumtaz (A)', EvaluasiDiniyah::hitungPredikat(100.0));

        // Nilai 80 — Jayyid Jiddan (B)
        $this->assertSame('Jayyid Jiddan (B)', EvaluasiDiniyah::hitungPredikat(80.0));

        // Nilai 70 — Jayyid (C)
        $this->assertSame('Jayyid (C)', EvaluasiDiniyah::hitungPredikat(70.0));

        // Nilai 60 — Maqbul (D)
        $this->assertSame('Maqbul (D)', EvaluasiDiniyah::hitungPredikat(60.0));

        // Nilai 59 — Perlu Bimbingan (E)
        $this->assertSame('Perlu Bimbingan (E)', EvaluasiDiniyah::hitungPredikat(59.0));
    }

    public function test_booted_hook_tidak_menimpa_predikat_hafalan_quran_dengan_kalkulasi_numerik(): void
    {
        // Pastikan jenis hafalan tidak dihitung ulang predikatnya dari nilai numerik
        $evaluasi = new EvaluasiDiniyah([
            'asrama_id' => $this->asrama->id,
            'anak_asuh_id' => $this->santri->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_QURAN,
            'materi' => 'Surah Al-Baqarah',
            'tanggal_evaluasi' => '2026-11-01',
            'nilai' => 95.0, // nilai tinggi, tapi predikat tidak boleh dihitung dari sini
            'predikat' => 'C', // predikat awal dari Staff Cabang (berbeda dari kalkulasi numerik 95)
            'status' => EvaluasiDiniyah::STATUS_PENDING,
            'diajukan_oleh' => $this->staffCabang->id,
            'diajukan_pada' => now(),
        ]);
        $evaluasi->save();

        // Predikat tidak boleh di-override oleh hitungPredikat(95) = Mumtaz (A)
        $this->assertSame('C', $evaluasi->fresh()->predikat);
    }

    public function test_booted_hook_menghitung_predikat_otomatis_untuk_nilai_diniyah(): void
    {
        // Untuk jenis selain Hafalan Qur'an & Hadis, predikat dihitung dari nilai numerik
        $evaluasi = new EvaluasiDiniyah([
            'asrama_id' => $this->asrama->id,
            'anak_asuh_id' => $this->santri->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_FIQIH,
            'materi' => 'Bab Thaharah',
            'tanggal_evaluasi' => '2026-11-01',
            'nilai' => 92.0,
            // predikat tidak diisi — harus dihitung otomatis dari nilai
            'status' => EvaluasiDiniyah::STATUS_PENDING,
            'diajukan_oleh' => $this->staffCabang->id,
            'diajukan_pada' => now(),
        ]);
        $evaluasi->save();

        // Predikat harus dihitung otomatis: 92 >= 90 = Mumtaz (A)
        $this->assertSame('Mumtaz (A)', $evaluasi->fresh()->predikat);
    }

    // =========================================================================
    // BAGIAN 11: Approve ganda dicegah (atomik)
    // =========================================================================

    public function test_approval_ganda_tidak_mengubah_data_jika_sudah_disetujui(): void
    {
        if (! Schema::hasColumn('evaluasi_diniyah', 'predikat_akhir')) {
            $this->markTestSkipped('Kolom predikat_akhir belum ada.');
        }

        $evaluasi = EvaluasiDiniyah::create([
            'asrama_id' => $this->asrama->id,
            'anak_asuh_id' => $this->santri->id,
            'jenis_evaluasi' => EvaluasiDiniyah::KATEGORI_QURAN,
            'materi' => 'Surah An-Naba',
            'tanggal_evaluasi' => '2026-11-01',
            'predikat' => 'A',
            'status' => EvaluasiDiniyah::STATUS_PENDING,
            'diajukan_oleh' => $this->staffCabang->id,
            'diajukan_pada' => now(),
        ]);

        // Approve pertama
        $this->actingAs($this->adminPendik)
            ->post(route('pusat.review-hafalan.approve', $evaluasi->id), [
                'catatan_evaluasi_pusat' => 'Disetujui pertama kali.',
            ]);

        $evaluasi->refresh();
        $waktuReviewPertama = $evaluasi->dinilai_pada;

        // Approve kedua (duplikat) — tidak boleh mengubah waktu review
        $this->actingAs($this->adminPendik)
            ->post(route('pusat.review-hafalan.approve', $evaluasi->id), [
                'catatan_evaluasi_pusat' => 'Percobaan approval duplikat.',
            ]);

        $evaluasi->refresh();
        // Waktu review tidak berubah karena approval ganda dicegah
        $this->assertEquals($waktuReviewPertama, $evaluasi->dinilai_pada);
        $this->assertSame(EvaluasiDiniyah::STATUS_DISETUJUI, $evaluasi->status);
    }
}
