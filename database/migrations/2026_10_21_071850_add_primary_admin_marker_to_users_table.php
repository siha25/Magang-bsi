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
        }

        $primaryAdmin = $matchingUsers->first();

        if ($primaryAdmin->role !== 'admin_pusat' || ! (bool) $primaryAdmin->status_aktif) {
            throw new RuntimeException('The confirmed primary administrator must have the admin_pusat role and an active account.');
        }

        if (Schema::hasColumn('users', 'is_primary_admin')) {
            throw new RuntimeException('Column [is_primary_admin] already exists; inspect the schema before retrying this migration.');
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('is_primary_admin')->nullable();
            $table->unique('is_primary_admin');
        });

        $updatedRows = DB::table('users')
            ->where('id', $primaryAdmin->id)
            ->whereNull('is_primary_admin')
            ->update(['is_primary_admin' => true]);

        if ($updatedRows !== 1) {
            throw new RuntimeException('Unable to mark exactly one confirmed primary administrator.');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['is_primary_admin']);
            $table->dropColumn('is_primary_admin');
        });
    }
};
