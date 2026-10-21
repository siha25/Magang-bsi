<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add only the evaluation date that is missing from the canonical schema.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('evaluasi_diniyah', 'tanggal_evaluasi')) {
            Schema::table('evaluasi_diniyah', function (Blueprint $table): void {
                $table->date('tanggal_evaluasi')->nullable()->after('materi');
            });
        }
    }

    /**
     * Retain the date because it may predate this migration or contain meaningful data.
     */
    public function down(): void
    {
        // Intentionally forward-only to avoid dropping existing evaluation dates.
    }
};
