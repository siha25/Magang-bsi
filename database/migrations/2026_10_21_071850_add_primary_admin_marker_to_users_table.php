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
        if (! Schema::hasTable('users')) {
            throw new RuntimeException('Primary administrator migration requires the [users] table.');
        }

        if (! Schema::hasColumn('users', 'is_primary_admin')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->boolean('is_primary_admin')->nullable();
                $table->unique('is_primary_admin', 'users_is_primary_admin_unique');
            });
        }

        $primaryAdmin = DB::table('users')
            ->where('id', 13)
            ->first(['id', 'name', 'email', 'role', 'status_aktif']);

        if ($primaryAdmin !== null) {
            DB::table('users')
                ->where('id', 13)
                ->whereNull('is_primary_admin')
                ->update(['is_primary_admin' => true]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique('users_is_primary_admin_unique');
            $table->dropColumn('is_primary_admin');
        });
    }
};
