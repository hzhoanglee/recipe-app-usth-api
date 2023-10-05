<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('recipes', function (Blueprint $blueprint) {
            $blueprint->string('ingredients', 2000)->change();
            $blueprint->string('steps', 5000)->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $blueprint) {
            $blueprint->string('ingredients')->change();
            $blueprint->string('steps')->change();
        });
    }
};
