<?php

namespace Database\Seeders;

use App\Models\KategoriKegiatan;
use Illuminate\Database\Seeder;

class KategoriKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nama' => 'Belajar',
                'kode' => 'belajar',
                'deskripsi' => 'Kegiatan belajar mandiri, bimbingan pelajaran, atau kursus santri',
                'is_active' => true,
            ],
            [
                'nama' => 'Muhadhoroh',
                'kode' => 'muhadhoroh',
                'deskripsi' => 'Latihan pidato, ceramah, dan public speaking santri',
                'is_active' => true,
            ],
            [
                'nama' => 'Olahraga',
                'kode' => 'olahraga',
                'deskripsi' => 'Aktivitas fisik, senam, futsal, lari, dan olahraga bersama',
                'is_active' => true,
            ],
            [
                'nama' => 'Acara Donatur',
                'kode' => 'acara_donatur',
                'deskripsi' => 'Pertemuan, santunan, buka puasa bersama, atau silaturahmi dengan donatur',
                'is_active' => true,
            ],
            [
                'nama' => 'Bersih-bersih',
                'kode' => 'bersih_bersih',
                'deskripsi' => 'Kerja bakti, piket kamar, dan pembersihan lingkungan asrama',
                'is_active' => true,
            ],
            [
                'nama' => 'Bimbingan Karakter',
                'kode' => 'bimbingan_karakter',
                'deskripsi' => 'Pembinaan akhlak, kajian tematik, dan konseling santri',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $cat) {
            KategoriKegiatan::updateOrCreate(
                ['kode' => $cat['kode']],
                [
                    'nama' => $cat['nama'],
                    'deskripsi' => $cat['deskripsi'],
                    'is_active' => $cat['is_active'],
                ]
            );
        }
    }
}
