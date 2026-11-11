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
        Schema::table('kegiatan_harian', function (Blueprint $table): void {
            if (! Schema::hasColumn('kegiatan_harian', 'foto_dokumentasi')) {
                $table->string('foto_dokumentasi', 255)->nullable()->after('catatan_tambahan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan_harian', function (Blueprint $table): void {
            if (Schema::hasColumn('kegiatan_harian', 'foto_dokumentasi')) {
                $table->dropColumn('foto_dokumentasi');
            }
        });
    }
};
