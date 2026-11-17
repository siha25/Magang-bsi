<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hapus kolom warna dari tabel kategori_kegiatan.
     * Badge kategori kini seragam bertema hijau (emerald).
     */
    public function up(): void
    {
        Schema::table('kategori_kegiatan', function (Blueprint $table) {
            $table->dropColumn('warna');
        });
    }

    /**
     * Kembalikan kolom warna jika migration di-rollback.
     */
    public function down(): void
    {
        Schema::table('kategori_kegiatan', function (Blueprint $table) {
            $table->string('warna', 20)->default('emerald')->after('deskripsi');
        });
    }
};
