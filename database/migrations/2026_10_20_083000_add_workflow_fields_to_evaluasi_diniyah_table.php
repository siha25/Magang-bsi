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
        Schema::table('evaluasi_diniyah', function (Blueprint $table): void {
            if (! Schema::hasColumn('evaluasi_diniyah', 'asrama_id')) {
                $table->foreignId('asrama_id')
                    ->after('id')
                    ->constrained('asrama')
                    ->cascadeOnDelete();
            }

            if (! Schema::hasColumn('evaluasi_diniyah', 'anak_asuh_id')) {
                $table->foreignId('anak_asuh_id')
                    ->after('asrama_id')
                    ->constrained('anak_asuh')
                    ->cascadeOnDelete();
            }

            if (! Schema::hasColumn('evaluasi_diniyah', 'jenis_evaluasi')) {
                $table->string('jenis_evaluasi', 50)
                    ->after('anak_asuh_id')
                    ->index();
            }

            if (! Schema::hasColumn('evaluasi_diniyah', 'materi')) {
                $table->string('materi', 255)
                    ->after('jenis_evaluasi');
            }

            if (! Schema::hasColumn('evaluasi_diniyah', 'tanggal_evaluasi')) {
                $table->date('tanggal_evaluasi')
                    ->nullable()
                    ->after('materi');
            }

            if (! Schema::hasColumn('evaluasi_diniyah', 'catatan_pengaju')) {
                $table->text('catatan_pengaju')
                    ->nullable()
                    ->after('tanggal_evaluasi');
            }

            if (! Schema::hasColumn('evaluasi_diniyah', 'status')) {
                $table->enum('status', ['pending', 'disetujui', 'perlu_revisi'])
                    ->default('pending')
                    ->after('catatan_pengaju')
                    ->index();
            }

            if (! Schema::hasColumn('evaluasi_diniyah', 'predikat')) {
                $table->enum('predikat', ['A', 'B', 'C'])
                    ->nullable()
                    ->after('status')
                    ->index();
            }

            if (! Schema::hasColumn('evaluasi_diniyah', 'catatan_revisi')) {
                $table->text('catatan_revisi')
                    ->nullable()
                    ->after('predikat');
            }

            if (! Schema::hasColumn('evaluasi_diniyah', 'diajukan_oleh')) {
                $table->foreignId('diajukan_oleh')
                    ->after('catatan_revisi')
                    ->constrained('users')
                    ->cascadeOnDelete();
            }

            if (! Schema::hasColumn('evaluasi_diniyah', 'diajukan_pada')) {
                $table->dateTime('diajukan_pada')
                    ->nullable()
                    ->after('diajukan_oleh');
            }

            if (! Schema::hasColumn('evaluasi_diniyah', 'dinilai_oleh')) {
                $table->foreignId('dinilai_oleh')
                    ->nullable()
                    ->after('diajukan_pada')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('evaluasi_diniyah', 'dinilai_pada')) {
                $table->dateTime('dinilai_pada')
                    ->nullable()
                    ->after('dinilai_oleh');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluasi_diniyah', function (Blueprint $table): void {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['asrama_id']);
                $table->dropForeign(['anak_asuh_id']);
                $table->dropForeign(['diajukan_oleh']);
                $table->dropForeign(['dinilai_oleh']);
            }

            $columns = [
                'asrama_id',
                'anak_asuh_id',
                'jenis_evaluasi',
                'materi',
                'tanggal_evaluasi',
                'catatan_pengaju',
                'status',
                'predikat',
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
