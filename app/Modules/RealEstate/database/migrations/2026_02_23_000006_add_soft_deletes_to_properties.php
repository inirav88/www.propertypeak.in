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

        if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'deleted_at')) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = Schema::hasTable('re_properties') ? 're_properties' : 'properties';

        if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'deleted_at')) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropSoftDeletes();
            });
        }
    }
};
