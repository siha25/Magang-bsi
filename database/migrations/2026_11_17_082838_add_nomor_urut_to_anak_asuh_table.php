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
        Schema::table('anak_asuh', function (Blueprint $table) {
            if (! Schema::hasColumn('anak_asuh', 'nomor_urut')) {
                $table->unsignedInteger('nomor_urut')->nullable()->after('nomor_induk');
                $table->index(['asrama_id', 'nomor_urut'], 'anak_asuh_asrama_nomor_urut_index');
                $table->index(['asrama_id', 'tanggal_masuk', 'nomor_urut'], 'anak_asuh_asrama_tgl_urut_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anak_asuh', function (Blueprint $table) {
            if (Schema::hasColumn('anak_asuh', 'nomor_urut')) {
                $table->dropIndex('anak_asuh_asrama_nomor_urut_index');
                $table->dropIndex('anak_asuh_asrama_tgl_urut_index');
                $table->dropColumn('nomor_urut');
            }
        });
    }
};
