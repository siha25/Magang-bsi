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
        Schema::create('anak_asuh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asrama_id')
                ->constrained('asrama')
                ->restrictOnDelete();
            $table->string('nomor_induk', 50)->unique();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenjang_pendidikan', ['SD', 'SMP', 'SMA']);
            $table->string('kelas', 50)->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anak_asuh');
    }
};
