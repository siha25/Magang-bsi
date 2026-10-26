<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void 
    {     
        Schema::table("evaluasi_diniyah", function (Blueprint $table): void {
            if (! Schema::hasColumn("evaluasi_diniyah", "asrama_id")) {
                $table->foreignId("asrama_id")     
                    ->after("id")
                    ->nullable()
                    ->constrained("asrama")        
                    ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table("evaluasi_diniyah", function (Blueprint $table): void {
            if (Schema::hasColumn("evaluasi_diniyah", "asrama_id")) {
                $table->dropForeign(["asrama_id"]);
                $table->dropColumn("asrama_id");
            }
        });
    }
};
