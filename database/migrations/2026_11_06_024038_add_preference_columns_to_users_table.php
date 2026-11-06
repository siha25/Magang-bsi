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
            $table->string('locale', 10)->default('id')->after('status_aktif');
            $table->string('theme', 20)->default('light')->after('locale');
            $table->string('timezone', 50)->default('Asia/Jakarta')->after('theme');
            $table->string('date_format', 20)->default('d/m/Y')->after('timezone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['locale', 'theme', 'timezone', 'date_format']);
        });
    }
};
