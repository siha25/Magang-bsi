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
        Schema::table('evaluasi_diniyah', function (Blueprint $table) {
            // Path file dokumen bukti hasil ujian (PDF/image)
            $table->string('dokumen_bukti')->nullable()->after('predikat_akhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluasi_diniyah', function (Blueprint $table) {
            $table->dropColumn('dokumen_bukti');
        });
    }
};
