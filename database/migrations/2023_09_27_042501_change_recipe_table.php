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
            $blueprint->string('level')->nullable()->default(null)->after('category');
            $blueprint->string('prep_time')->nullable()->default(null)->after('category');
            $blueprint->string('cook_time')->nullable()->default(null)->after('category');
            $blueprint->float('review')->nullable()->default(null)->after('category');
            $blueprint->string('serving')->nullable()->default(null)->after('category');
            $blueprint->string('original_url')->nullable()->default(null)->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $blueprint) {
            $blueprint->dropColumn('level');
            $blueprint->dropColumn('prep_time');
            $blueprint->dropColumn('cook_time');
            $blueprint->dropColumn('review');
            $blueprint->dropColumn('serving');
            $blueprint->dropColumn('original_url');
        });
    }
};
