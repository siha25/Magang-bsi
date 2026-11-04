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
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'is_primary_admin')) {
                $table->boolean('is_primary_admin')->nullable()->unique('users_is_primary_admin_unique')->after('role');
            }
        });

        $primaryAdmin = DB::table('users')
            ->whereRaw('LOWER(email) = ?', ['adminpusat1@gmail.com'])
            ->first();

        if ($primaryAdmin && ! DB::table('users')->where('is_primary_admin', true)->exists()) {
            DB::table('users')
                ->where('id', $primaryAdmin->id)
                ->update(['is_primary_admin' => true]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'is_primary_admin')) {
                if (DB::getDriverName() !== 'sqlite') {
                    $table->dropUnique('users_is_primary_admin_unique');
                }
                $table->dropColumn('is_primary_admin');
            }
        });
    }
};
