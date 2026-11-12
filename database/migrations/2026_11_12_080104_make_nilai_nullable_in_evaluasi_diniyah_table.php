<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ubah column nilai menjadi nullable karena hafalan Al-Qur'an dan Hadis
     * menggunakan predikat manual (A/B/C) tanpa nilai numerik.
     */
    public function up(): void
    {
        Schema::table('evaluasi_diniyah', function (Blueprint $table) {
            $table->decimal('nilai', 5, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluasi_diniyah', function (Blueprint $table) {
            $table->decimal('nilai', 5, 2)->nullable(false)->change();
        });
    }
};
