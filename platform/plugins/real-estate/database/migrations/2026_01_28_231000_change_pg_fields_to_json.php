<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('re_properties', function (Blueprint $table) {
            // Change columns to JSON to support multiple values
            $table->json('pg_occupancy_type')->nullable()->change();
            $table->json('food_type')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('re_properties', function (Blueprint $table) {
            // Revert to string (Warning: Data loss possible if arrays are stored)
            $table->string('pg_occupancy_type', 255)->nullable()->change();
            $table->string('food_type', 255)->nullable()->change();
        });
    }
};
