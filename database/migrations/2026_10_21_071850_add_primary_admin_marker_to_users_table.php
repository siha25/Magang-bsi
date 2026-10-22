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

        $primaryAdmin = DB::table('users')
            ->where('id', 13)
            ->first(['id', 'name', 'email', 'role', 'status_aktif']);

        if ($primaryAdmin === null) {
            throw new RuntimeException('Primary administrator migration requires account ID [13]. No account was found.');
        }

        $mismatchedFields = [];

        if ((int) $primaryAdmin->id !== 13) {
            $mismatchedFields[] = 'ID';
        }

        if (strcasecmp((string) $primaryAdmin->name, 'Siti Hasanah') !== 0) {
            $mismatchedFields[] = 'name';
        }

        if (strcasecmp((string) $primaryAdmin->email, 'Adminpusat1@gmail.com') !== 0) {
            $mismatchedFields[] = 'email';
        }

        if ($primaryAdmin->role !== 'admin_pusat') {
            $mismatchedFields[] = 'role';
        }

        if (! (bool) $primaryAdmin->status_aktif) {
            $mismatchedFields[] = 'active status';
        }

        if ($mismatchedFields !== []) {
            throw new RuntimeException(sprintf(
                'Primary administrator migration stopped before schema changes: account ID [13] does not match the confirmed identity. Mismatched fields: %s.',
                implode(', ', $mismatchedFields),
            ));
        }

        if (Schema::hasColumn('users', 'is_primary_admin')) {
            throw new RuntimeException(
                'Column [is_primary_admin] already exists; inspect the schema and migration state for a partial or prior application before retrying.',
            );
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('is_primary_admin')->nullable();
            $table->unique('is_primary_admin', 'users_is_primary_admin_unique');
        });

        $updatedRows = DB::table('users')
            ->where('id', 13)
            ->whereNull('is_primary_admin')
            ->update(['is_primary_admin' => true]);

        if ($updatedRows !== 1) {
            throw new RuntimeException(
                'Primary administrator schema was added, but account ID [13] was not marked. Inspect the schema and data before recovery; do not rerun this migration blindly.',
            );
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
