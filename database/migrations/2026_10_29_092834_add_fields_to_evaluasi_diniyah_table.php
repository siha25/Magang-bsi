<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('evaluasi_diniyah', function (Blueprint $table) {
            if (! Schema::hasColumn('evaluasi_diniyah', 'jenis_evaluasi')) {
                $table->string('jenis_evaluasi')->nullable()->after('asrama_id');
            }
            if (! Schema::hasColumn('evaluasi_diniyah', 'materi')) {
                $table->string('materi')->nullable()->after('jenis_evaluasi');
            }
            if (! Schema::hasColumn('evaluasi_diniyah', 'tanggal_evaluasi')) {
                $table->date('tanggal_evaluasi')->nullable()->after('materi');
            }
            if (! Schema::hasColumn('evaluasi_diniyah', 'nilai')) {
                $table->decimal('nilai', 5, 2)->default(0)->after('tanggal_evaluasi');
            }
            if (! Schema::hasColumn('evaluasi_diniyah', 'predikat')) {
                $table->string('predikat', 50)->nullable()->after('nilai');
            }
            if (! Schema::hasColumn('evaluasi_diniyah', 'catatan_pengaju')) {
                $table->text('catatan_pengaju')->nullable()->after('predikat');
            }
            if (! Schema::hasColumn('evaluasi_diniyah', 'status')) {
                $table->string('status', 30)->default('disetujui')->after('catatan_pengaju');
            }
            if (! Schema::hasColumn('evaluasi_diniyah', 'catatan_revisi')) {
                $table->text('catatan_revisi')->nullable()->after('status');
            }
            if (! Schema::hasColumn('evaluasi_diniyah', 'diajukan_oleh')) {
                $table->foreignId('diajukan_oleh')->nullable()->after('catatan_revisi')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('evaluasi_diniyah', 'diajukan_pada')) {
                $table->timestamp('diajukan_pada')->nullable()->after('diajukan_oleh');
            }
            if (! Schema::hasColumn('evaluasi_diniyah', 'dinilai_oleh')) {
                $table->foreignId('dinilai_oleh')->nullable()->after('diajukan_pada')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('evaluasi_diniyah', 'dinilai_pada')) {
                $table->timestamp('dinilai_pada')->nullable()->after('dinilai_oleh');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluasi_diniyah', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                if (Schema::hasColumn('evaluasi_diniyah', 'diajukan_oleh')) {
                    $table->dropForeign(['diajukan_oleh']);
                }
                if (Schema::hasColumn('evaluasi_diniyah', 'dinilai_oleh')) {
                    $table->dropForeign(['dinilai_oleh']);
                }
            }

            $columns = [
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
            ];

            $columnsToDrop = array_filter(
                $columns,
                static fn (string $column): bool => Schema::hasColumn('evaluasi_diniyah', $column)
            );

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
