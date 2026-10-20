<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengonversi role lama secara aman tanpa menghapus akun atau mengubah password:
     * - admin_asrama -> admin_cabang
     * - pimpinan -> pimpinan_pusat
     */
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'admin_asrama')
            ->update(['role' => 'admin_cabang']);

        DB::table('users')
            ->where('role', 'pimpinan')
            ->update(['role' => 'pimpinan_pusat']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->where('role', 'admin_cabang')
            ->update(['role' => 'admin_asrama']);

        DB::table('users')
            ->where('role', 'pimpinan_pusat')
            ->update(['role' => 'pimpinan']);
    }
};
