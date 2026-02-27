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

        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                // Add column if not exists (as fallback)
                if (!Schema::hasColumn($tableName, 'slug')) {
                    $table->string('slug', 255)->nullable()->after('name');
                }
                
                // Only add unique index if no duplicates exist
                $duplicates = \Illuminate\Support\Facades\DB::table($tableName)
                    ->select('slug')
                    ->whereNotNull('slug')
                    ->where('slug', '!=', '')
                    ->groupBy('slug')
                    ->havingRaw('COUNT(*) > 1')
                    ->count();

                if ($duplicates === 0) {
                    try {
                        $table->unique('slug');
                    } catch (\Exception $e) {
                        // Index might already exist
                    }
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
