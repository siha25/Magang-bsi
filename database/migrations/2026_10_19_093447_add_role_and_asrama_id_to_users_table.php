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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->after('email');
            $table->foreignId('asrama_id')
                ->nullable()
                ->after('role')
                ->constrained('asrama')
                ->nullOnDelete();
            $table->boolean('status_aktif')->default(true)->after('asrama_id');

            $table->index(['role', 'status_aktif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['asrama_id']);
            $table->dropIndex(['role', 'status_aktif']);
            $table->dropColumn(['role', 'asrama_id', 'status_aktif']);
        });
    }
};
