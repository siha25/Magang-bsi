<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AsramaCodeGenerator
{
    public function next(): string
    {
        return DB::transaction(function (): string {
            $counter = DB::table('asrama_code_counters')
                ->where('id', 1)
                ->lockForUpdate()
                ->first();

            if ($counter === null) {
                $lastNumber = DB::table('asrama')
                    ->where('kode_asrama', 'like', 'ASR-%')
                    ->pluck('kode_asrama')
                    ->reduce(function (int $highestNumber, string $code): int {
                        if (preg_match('/\AASR-(\d+)\z/i', $code, $matches) !== 1) {
                            return $highestNumber;
                        }

                        return max($highestNumber, (int) $matches[1]);
                    }, 0);

                DB::table('asrama_code_counters')->updateOrInsert(
                    ['id' => 1],
                    ['last_number' => $lastNumber]
                );

                $counter = DB::table('asrama_code_counters')
                    ->where('id', 1)
                    ->lockForUpdate()
                    ->first();
            }

            $nextNumber = (int) $counter->last_number + 1;

            DB::table('asrama_code_counters')
                ->where('id', 1)
                ->update(['last_number' => $nextNumber]);

            return sprintf('ASR-%03d', $nextNumber);
        });
    }
}
