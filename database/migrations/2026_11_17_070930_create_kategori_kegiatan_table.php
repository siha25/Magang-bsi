<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('kategori_kegiatan')) {
            Schema::create('kategori_kegiatan', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 100)->unique();
                $table->string('kode', 50)->unique();
                $table->text('deskripsi')->nullable();
                $table->string('warna', 30)->default('emerald');
                $table->boolean('is_active')->default(true);
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();

                $table->index('kode');
                $table->index('is_active');
            });
        }

        // Ubah kolom kategori di kegiatan_harian dari enum menjadi varchar agar kategori baru fleksibel
        if (Schema::hasTable('kegiatan_harian')) {
            try {
                DB::statement('ALTER TABLE kegiatan_harian MODIFY COLUMN kategori VARCHAR(50) NOT NULL');
            } catch (Throwable) {
                // Abaikan jika sudah bertipe varchar
            }

            if (! Schema::hasColumn('kegiatan_harian', 'kategori_kegiatan_id')) {
                Schema::table('kegiatan_harian', function (Blueprint $table) {
                    $table->foreignId('kategori_kegiatan_id')
                        ->nullable()
                        ->after('asrama_id')
                        ->constrained('kategori_kegiatan')
                        ->nullOnDelete();
                });
            }
        }

        // Inisialisasi 5 kategori kegiatan bawaan
        $defaultKategoris = [
            [
                'nama' => 'Belajar',
                'kode' => 'belajar',
                'deskripsi' => 'Kegiatan akademik, bimbingan belajar, tahsin, dan pengkajian ilmu santri.',
                'warna' => 'emerald',
            ],
            [
                'nama' => 'Muhadhoroh',
                'kode' => 'muhadhoroh',
                'deskripsi' => 'Latihan pidato, public speaking, kultum, dan pengembangan mental santri.',
                'warna' => 'blue',
            ],
            [
                'nama' => 'Olahraga',
                'kode' => 'olahraga',
                'deskripsi' => 'Aktivitas fisik jasmani, senam, lari pagi, dan pertandingan persahabatan santri.',
                'warna' => 'amber',
            ],
            [
                'nama' => 'Acara Donatur',
                'kode' => 'acara_donatur',
                'deskripsi' => 'Silaturahmi, santunan, buka bersama, dan ramah tamah bersama mitra yayasan.',
                'warna' => 'purple',
            ],
            [
                'nama' => 'Bersih-bersih',
                'kode' => 'bersih_bersih',
                'deskripsi' => 'Gotong royong, kerja bakti asrama, piket kamar, dan kebersihan lingkungan.',
                'warna' => 'teal',
            ],
        ];

        foreach ($defaultKategoris as $item) {
            $existingId = DB::table('kategori_kegiatan')->where('kode', $item['kode'])->value('id');
            if (! $existingId) {
                $existingId = DB::table('kategori_kegiatan')->insertGetId([
                    'nama' => $item['nama'],
                    'kode' => $item['kode'],
                    'deskripsi' => $item['deskripsi'],
                    'warna' => $item['warna'],
                    'is_active' => true,
                    'created_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Hubungkan data historis kegiatan_harian
            DB::table('kegiatan_harian')
                ->where('kategori', $item['kode'])
                ->update(['kategori_kegiatan_id' => $existingId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('kegiatan_harian') && Schema::hasColumn('kegiatan_harian', 'kategori_kegiatan_id')) {
            Schema::table('kegiatan_harian', function (Blueprint $table) {
                $table->dropForeign(['kategori_kegiatan_id']);
                $table->dropColumn('kategori_kegiatan_id');
            });
        }

        Schema::dropIfExists('kategori_kegiatan');
    }
};
