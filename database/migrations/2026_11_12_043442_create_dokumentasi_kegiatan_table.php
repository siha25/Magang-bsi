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
        if (! Schema::hasTable('dokumentasi_kegiatan')) {
            Schema::create('dokumentasi_kegiatan', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('kegiatan_id')->constrained('kegiatan_harian')->cascadeOnDelete();
                $table->string('path_file', 255);
                $table->string('nama_asli', 255)->nullable();
                $table->unsignedInteger('ukuran_byte')->default(0);
                $table->unsignedTinyInteger('urutan')->default(0);
                $table->timestamps();

                $table->index(['kegiatan_id', 'urutan']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_kegiatan');
    }
};
