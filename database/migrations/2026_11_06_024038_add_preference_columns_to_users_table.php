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
                $table->string('date_format', 20)->default('d/m/Y')->after('timezone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $cols = array_filter(['locale', 'theme', 'timezone', 'date_format'], fn ($c) => Schema::hasColumn('users', $c));
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
