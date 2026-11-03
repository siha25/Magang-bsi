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
            if (! Schema::hasColumn('evaluasi_diniyah', 'catatan_evaluasi_pusat')) {
                $table->text('catatan_evaluasi_pusat')->nullable()->after('catatan_revisi');
            }
            if (! Schema::hasColumn('evaluasi_diniyah', 'status_tindak_lanjut')) {
                $table->string('status_tindak_lanjut', 50)->nullable()->default('belum_ditindaklanjuti')->after('catatan_evaluasi_pusat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluasi_diniyah', function (Blueprint $table) {
            if (Schema::hasColumn('evaluasi_diniyah', 'status_tindak_lanjut')) {
                $table->dropColumn('status_tindak_lanjut');
            }
            if (Schema::hasColumn('evaluasi_diniyah', 'catatan_evaluasi_pusat')) {
                $table->dropColumn('catatan_evaluasi_pusat');
            }
        });
    }
};
