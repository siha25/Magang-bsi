<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void 
    {     
        Schema::table("users", function (Blueprint $table): void {
            if (! Schema::hasColumn("users", "is_primary_admin")) {
                $table->boolean("is_primary_admin")->default(false)->after("role");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function (Blueprint $table): void {
            if (Schema::hasColumn("users", "is_primary_admin")) {
                $table->dropColumn("is_primary_admin");
            }
        });
    }
};
