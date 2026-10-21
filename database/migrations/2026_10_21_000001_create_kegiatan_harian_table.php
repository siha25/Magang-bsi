<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kegiatan_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asrama_id')->constrained('asrama')->cascadeOnDelete();
            $table->date('tanggal_kegiatan');
            $table->enum('kategori', ['belajar', 'muhadhoroh', 'olahraga', 'acara_donatur', 'bersih_bersih']);
            $table->string('nama_kegiatan');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('jumlah_peserta')->default(0);
            $table->text('catatan_tambahan')->nullable();
            $table->foreignId('penginput_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['asrama_id', 'tanggal_kegiatan']);
            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_harian');
    }
};
