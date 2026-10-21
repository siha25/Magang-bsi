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
        Schema::create('asrama_code_counters', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->unsignedBigInteger('last_number')->default(0);
        });

        $lastNumber = DB::table('asrama')
            ->where('kode_asrama', 'like', 'ASR-%')
            ->pluck('kode_asrama')
            ->reduce(function (int $highestNumber, string $code): int {
                if (preg_match('/\AASR-(\d+)\z/i', $code, $matches) !== 1) {
                    return $highestNumber;
                }

                return max($highestNumber, (int) $matches[1]);
            }, 0);

        DB::table('asrama_code_counters')->insert([
            'id' => 1,
            'last_number' => $lastNumber,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        throw new LogicException('The asrama code counter cannot be rolled back without risking code reuse.');
    }
};
