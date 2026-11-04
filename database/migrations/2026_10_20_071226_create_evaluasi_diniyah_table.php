<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluasi_diniyah', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('anak_asuh_id')->constrained('anak_asuh')->cascadeOnDelete();
            $table->foreignId('asrama_id')->nullable()->constrained('asrama')->nullOnDelete();
            $table->string('bulan');
            $table->integer('tahun');
            $table->string('semester')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi_diniyah');
    }
};
