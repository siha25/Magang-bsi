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
            $table->string('jenis_evaluasi')->nullable()->after('asrama_id');
            $table->string('materi')->nullable()->after('jenis_evaluasi');
            $table->date('tanggal_evaluasi')->nullable()->after('materi');
            $table->decimal('nilai', 5, 2)->default(0)->after('tanggal_evaluasi');
            $table->string('predikat', 50)->nullable()->after('nilai');
            $table->text('catatan_pengaju')->nullable()->after('predikat');
            $table->string('status', 30)->default('disetujui')->after('catatan_pengaju');
            $table->text('catatan_revisi')->nullable()->after('status');
            $table->foreignId('diajukan_oleh')->nullable()->after('catatan_revisi')->constrained('users')->nullOnDelete();
            $table->timestamp('diajukan_pada')->nullable()->after('diajukan_oleh');
            $table->foreignId('dinilai_oleh')->nullable()->after('diajukan_pada')->constrained('users')->nullOnDelete();
            $table->timestamp('dinilai_pada')->nullable()->after('dinilai_oleh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluasi_diniyah', function (Blueprint $table) {
            $table->dropForeign(['diajukan_oleh']);
            $table->dropForeign(['dinilai_oleh']);
            $table->dropColumn([
                'jenis_evaluasi',
                'materi',
                'tanggal_evaluasi',
                'nilai',
                'predikat',
                'catatan_pengaju',
                'status',
                'catatan_revisi',
                'diajukan_oleh',
                'diajukan_pada',
                'dinilai_oleh',
                'dinilai_pada',
            ]);
        });
    }
};
