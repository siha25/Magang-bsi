<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Rancang migration incremental yang aman untuk workflow evaluasi diniyah & hafalan.
     */
    public function up(): void
    {
        Schema::table('evaluasi_diniyah', function (Blueprint $table) {
            $table->foreignId('asrama_id')
                ->after('id')
                ->constrained('asrama')
                ->cascadeOnDelete();

            $table->foreignId('anak_asuh_id')
                ->after('asrama_id')
                ->constrained('anak_asuh')
                ->cascadeOnDelete();

            $table->enum('kategori', ['hafalan_quran', 'hafalan_hadis', 'pengetahuan_diniyah'])
                ->after('anak_asuh_id')
                ->index();

            $table->string('judul_materi', 255)->after('kategori');

            $table->date('tanggal_evaluasi')->after('judul_materi');

            $table->text('keterangan_pengajuan')->nullable()->after('tanggal_evaluasi');

            $table->enum('status', ['pending', 'disetujui', 'perlu_revisi'])
                ->default('pending')
                ->after('keterangan_pengajuan')
                ->index();

            $table->enum('predikat', ['A', 'B', 'C'])
                ->nullable()
                ->after('status')
                ->index();

            $table->text('catatan_revisi')->nullable()->after('predikat');

            $table->foreignId('penginput_id')
                ->after('catatan_revisi')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('penilai_id')
                ->nullable()
                ->after('penginput_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('disetujui_pada')->nullable()->after('penilai_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluasi_diniyah', function (Blueprint $table) {
            $table->dropForeign(['asrama_id']);
            $table->dropForeign(['anak_asuh_id']);
            $table->dropForeign(['penginput_id']);
            $table->dropForeign(['penilai_id']);
            $table->dropColumn([
                'asrama_id',
                'anak_asuh_id',
                'kategori',
                'judul_materi',
                'tanggal_evaluasi',
                'keterangan_pengajuan',
                'status',
                'predikat',
                'catatan_revisi',
                'penginput_id',
                'penilai_id',
                'disetujui_pada',
            ]);
        });
    }
};
