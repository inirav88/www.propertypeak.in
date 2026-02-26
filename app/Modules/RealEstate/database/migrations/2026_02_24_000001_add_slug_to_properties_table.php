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
        $tableName = Schema::hasTable('re_properties') ? 're_properties' : 'properties';

        if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'slug')) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                $table->string('slug', 255)->nullable()->after('name');
                
                // Add unique index separately to handle existing data
                try {
                    $table->unique('slug');
                } catch (\Exception $e) {
                    // Index might already exist
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = Schema::hasTable('re_properties') ? 're_properties' : 'properties';

        if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'slug')) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropColumn('slug');
            });
        }
    }
};
