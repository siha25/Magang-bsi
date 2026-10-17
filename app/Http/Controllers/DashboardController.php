<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama divisi pendidikan anak asuh.
     */
    public function index(): View
    {
        $periode = [
            'bulan' => 'Oktober',
            'tahun' => '2026',
            'semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
        ];

        $pengurus = [
            'nama' => 'Ust. Ahmad Fauzi, S.Pd.I',
            'jabatan' => 'Koordinator Divisi Pendidikan',
            'yayasan' => 'Yayasan Rumah Harapan',
            'email' => 'ahmad.fauzi@rumahharapan.org',
            'avatar' => null,
            'status' => 'Aktif Bertugas',
        ];

        $stats = [
            'total_anak' => 248,
            'total_cabang' => 6,
            'jenjang' => [
                'sd' => 82,
                'smp' => 96,
                'sma' => 70,
            ],
            'evaluasi_bulan_ini' => [
                'selesai' => 228,
                'total' => 248,
                'persentase' => 91.9,
                'keterangan' => '228 dari 248 anak telah dievaluasi',
            ],
            'target_hafalan' => [
                'tercapai' => 194,
                'total' => 248,
                'persentase' => 78.2,
                'keterangan' => '194 anak memenuhi target juz',
            ],
            'evaluasi_belum_lengkap' => [
                'jumlah' => 20,
                'keterangan' => '20 anak perlu tindak lanjut',
                'status' => 'Perhatian Pengurus',
            ],
        ];

        $cabangList = [
            [
                'id' => 1,
                'nama' => 'Cabang Bandung (Pusat)',
                'lokasi' => 'Jl. Cisangkuy No. 42, Bandung',
                'kepala_asrama' => 'Ust. H. Ridwan Kamil, S.Sos.I',
                'total_anak' => 52,
                'jenjang' => ['sd' => 16, 'smp' => 20, 'sma' => 16],
                'target_hafalan_persen' => 84,
                'evaluasi_status' => 'Lengkap',
                'evaluasi_progress' => 100,
                'rata_nilai_diniyah' => 88.5,
                'telepon' => '0812-2345-6789',
            ],
            [
                'id' => 2,
                'nama' => 'Cabang Bogor',
                'lokasi' => 'Jl. Pajajaran Indah No. 18, Bogor',
                'kepala_asrama' => 'Ust. Muhammad Ilham, M.Pd',
                'total_anak' => 44,
                'jenjang' => ['sd' => 14, 'smp' => 18, 'sma' => 12],
                'target_hafalan_persen' => 81,
                'evaluasi_status' => 'Lengkap',
                'evaluasi_progress' => 100,
                'rata_nilai_diniyah' => 86.0,
                'telepon' => '0813-3456-7890',
            ],
            [
                'id' => 3,
                'nama' => 'Cabang Depok',
                'lokasi' => 'Jl. Margonda Raya No. 105, Depok',
                'kepala_asrama' => 'Ust. Farhan Al-Ghifari, S.Pd',
                'total_anak' => 38,
                'jenjang' => ['sd' => 12, 'smp' => 16, 'sma' => 10],
                'target_hafalan_persen' => 76,
                'evaluasi_status' => 'Berjalan',
                'evaluasi_progress' => 89,
                'rata_nilai_diniyah' => 83.2,
                'telepon' => '0815-4567-8901',
            ],
            [
                'id' => 4,
                'nama' => 'Cabang Sukabumi',
                'lokasi' => 'Jl. Bhayangkara No. 27, Sukabumi',
                'kepala_asrama' => 'Ustadzah Nurul Hidayati, S.Ag',
                'total_anak' => 42,
                'jenjang' => ['sd' => 18, 'smp' => 14, 'sma' => 10],
                'target_hafalan_persen' => 73,
                'evaluasi_status' => 'Berjalan',
                'evaluasi_progress' => 85,
                'rata_nilai_diniyah' => 81.7,
                'telepon' => '0818-5678-9012',
            ],
            [
                'id' => 5,
                'nama' => 'Cabang Yogyakarta',
                'lokasi' => 'Jl. Kaliurang KM 7.5, Sleman, DIY',
                'kepala_asrama' => 'Ust. Dwi Prasetyo, S.Th.I',
                'total_anak' => 36,
                'jenjang' => ['sd' => 10, 'smp' => 14, 'sma' => 12],
                'target_hafalan_persen' => 80,
                'evaluasi_status' => 'Lengkap',
                'evaluasi_progress' => 100,
                'rata_nilai_diniyah' => 85.8,
                'telepon' => '0819-6789-0123',
            ],
            [
                'id' => 6,
                'nama' => 'Cabang Surabaya',
                'lokasi' => 'Jl. Jemursari Selatan No. 14, Surabaya',
                'kepala_asrama' => 'Ust. Syahrul Ramadhan, Lc',
                'total_anak' => 36,
                'jenjang' => ['sd' => 12, 'smp' => 14, 'sma' => 10],
                'target_hafalan_persen' => 68,
                'evaluasi_status' => 'Belum Lengkap',
                'evaluasi_progress' => 65,
                'rata_nilai_diniyah' => 79.4,
                'telepon' => '0821-7890-1234',
            ],
        ];

        $targetKategori = [
            [
                'judul' => "Hafalan Al-Qur'an",
                'subjudul' => 'Target minimal 1 juz per semester per anak',
                'tercapai_persen' => 78,
                'detail' => '194 anak on-track, 54 anak perlu bimbingan',
                'warna' => 'emerald',
            ],
            [
                'judul' => 'Hafalan Hadis Arbain',
                'subjudul' => 'Target 15-20 hadis per semester',
                'tercapai_persen' => 72,
                'detail' => '178 anak lulus ujian setoran hadis',
                'warna' => 'amber',
            ],
            [
                'judul' => 'Pengetahuan Diniyah (Fiqih & Aqidah)',
                'subjudul' => 'Materi standar kurikulum Rumah Harapan',
                'tercapai_persen' => 89,
                'detail' => 'Nilai rata-rata seluruh cabang 84.1',
                'warna' => 'teal',
            ],
            [
                'judul' => 'Disiplin Ibadah & Sholat Berjamaah',
                'subjudul' => 'Pemantauan buku mutabaah harian',
                'tercapai_persen' => 96,
                'detail' => 'Konsistensi tinggi di 6 asrama',
                'warna' => 'orange',
            ],
        ];

        $aktivitasTerbaru = [
            [
                'tipe' => 'quran',
                'judul' => 'Setoran Hafalan Juz 29 tuntas oleh 4 anak',
                'cabang' => 'Cabang Bandung',
                'waktu' => '25 menit lalu',
                'pembina' => 'Ust. Ridwan Kamil',
                'status' => 'Selesai',
            ],
            [
                'tipe' => 'evaluasi',
                'judul' => 'Input 18 lembar evaluasi diniyah bulanan',
                'cabang' => 'Cabang Bogor',
                'waktu' => '1 jam lalu',
                'pembina' => 'Ust. Muhammad Ilham',
                'status' => 'Tervalidasi',
            ],
            [
                'tipe' => 'kesehatan',
                'judul' => 'Pemeriksaan kesehatan rutin & imunisasi',
                'cabang' => 'Cabang Depok',
                'waktu' => '3 jam lalu',
                'pembina' => 'dr. Amalia (Tim Medis)',
                'status' => 'Selesai',
            ],
            [
                'tipe' => 'hadis',
                'judul' => 'Munaqasyah Hadis Arbain nomor 1-20',
                'cabang' => 'Cabang Yogyakarta',
                'waktu' => '5 jam lalu',
                'pembina' => 'Ust. Dwi Prasetyo',
                'status' => 'Berhasil',
            ],
            [
                'tipe' => 'pembinaan',
                'judul' => 'Sesi konseling motivasi belajar & adab asrama',
                'cabang' => 'Cabang Sukabumi',
                'waktu' => 'Kemarin, 16:30',
                'pembina' => 'Ustadzah Nurul H.',
                'status' => 'Selesai',
            ],
        ];

        return view('dashboard', compact(
            'periode',
            'pengurus',
            'stats',
            'cabangList',
            'targetKategori',
            'aktivitasTerbaru'
        ));
    }
}
