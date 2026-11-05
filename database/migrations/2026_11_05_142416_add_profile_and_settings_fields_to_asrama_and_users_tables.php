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
        Schema::table('asrama', function (Blueprint $table): void {
            if (! Schema::hasColumn('asrama', 'email')) {
                $table->string('email', 150)->nullable()->after('nomor_telepon');
            }
            if (! Schema::hasColumn('asrama', 'jam_operasional')) {
                $table->string('jam_operasional', 100)->nullable()->default('08:00 - 17:00 WIB')->after('email');
            }
        });

        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'locale')) {
                $table->string('locale', 10)->default('id')->after('status_aktif');
            }
            if (! Schema::hasColumn('users', 'theme')) {
                $table->string('theme', 20)->default('light')->after('locale');
            }
            if (! Schema::hasColumn('users', 'timezone')) {
                $table->string('timezone', 50)->default('Asia/Jakarta')->after('theme');
            }
            if (! Schema::hasColumn('users', 'date_format')) {
                $table->string('date_format', 30)->default('d/m/Y')->after('timezone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asrama', function (Blueprint $table): void {
            $columns = [];
            if (Schema::hasColumn('asrama', 'jam_operasional')) {
                $columns[] = 'jam_operasional';
            }
            if (Schema::hasColumn('asrama', 'email')) {
                $columns[] = 'email';
            }
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('users', function (Blueprint $table): void {
            $columns = [];
            if (Schema::hasColumn('users', 'date_format')) {
                $columns[] = 'date_format';
            }
            if (Schema::hasColumn('users', 'timezone')) {
                $columns[] = 'timezone';
            }
            if (Schema::hasColumn('users', 'theme')) {
                $columns[] = 'theme';
            }
            if (Schema::hasColumn('users', 'locale')) {
                $columns[] = 'locale';
            }
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
