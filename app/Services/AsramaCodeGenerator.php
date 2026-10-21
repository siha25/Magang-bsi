<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use RuntimeException;

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
                throw new RuntimeException('The asrama code counter has not been initialized.');
            }

            $nextNumber = (int) $counter->last_number + 1;

            DB::table('asrama_code_counters')
                ->where('id', 1)
                ->update(['last_number' => $nextNumber]);

            return sprintf('ASR-%03d', $nextNumber);
        });
    }
}
