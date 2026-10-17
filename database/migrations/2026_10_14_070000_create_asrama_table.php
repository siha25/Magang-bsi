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
        Schema::create('asrama', function (Blueprint $table) {
            $table->id();
            $table->string('kode_asrama', 30)->unique();
            $table->string('nama_asrama');
            $table->string('wilayah', 100)->nullable()->index();
            $table->text('alamat')->nullable();
            $table->string('penanggung_jawab', 150)->nullable();
            $table->string('nomor_telepon', 30)->nullable();
            $table->boolean('status_aktif')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asrama');
    }
};
