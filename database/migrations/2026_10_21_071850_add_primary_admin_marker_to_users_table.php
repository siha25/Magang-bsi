<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
<<<<<<< HEAD
        $matchingUsers = DB::table('users')
            ->whereRaw('LOWER(name) = ?', ['siti hasanah'])
            ->whereRaw('LOWER(email) = ?', ['adminpusat1@gmail.com'])
            ->get(['id', 'role', 'status_aktif']);

        if ($matchingUsers->isEmpty()) {
            DB::table('users')->insert([
                'name' => 'Siti Hasanah',
                'email' => 'adminpusat1@gmail.com',
                'password' => Hash::make('isolated-testing-password'),
                'role' => 'admin_pusat',
                'asrama_id' => null,
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $matchingUsers = DB::table('users')
                ->whereRaw('LOWER(name) = ?', ['siti hasanah'])
                ->whereRaw('LOWER(email) = ?', ['adminpusat1@gmail.com'])
                ->get(['id', 'role', 'status_aktif']);
        }

        if ($matchingUsers->count() !== 1) {
            throw new RuntimeException(sprintf(
                'Primary administrator migration requires exactly one Siti Hasanah account with the confirmed email; found %d.',
                $matchingUsers->count(),
            ));
=======
        if (! Schema::hasTable('users')) {
            throw new RuntimeException('Primary administrator migration requires the [users] table.');
>>>>>>> bc40987ffdf55dc72a252fac2b9349a4dccba0bb
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
