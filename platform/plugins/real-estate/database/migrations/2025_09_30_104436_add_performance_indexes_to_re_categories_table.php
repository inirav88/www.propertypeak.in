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
        if (Schema::hasTable('re_categories')) {
            Schema::table('re_categories', function (Blueprint $table): void {
                // Add index for status column (used in category filtering)
                $table->index('status', 'idx_re_categories_status');

                // Add index for parent_id (used for hierarchical queries)
                $table->index('parent_id', 'idx_re_categories_parent_id');

                // Add composite index for common query pattern
                $table->index(['status', 'parent_id', 'order'], 'idx_re_categories_status_parent_order');

                // Add index for is_default (used in sorting)
                $table->index('is_default', 'idx_re_categories_is_default');

                // Add index for name (useful for search queries)
                $table->index('name', 'idx_re_categories_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('re_categories')) {
            Schema::table('re_categories', function (Blueprint $table): void {
                $table->dropIndex('idx_re_categories_status');
                $table->dropIndex('idx_re_categories_parent_id');
                $table->dropIndex('idx_re_categories_status_parent_order');
                $table->dropIndex('idx_re_categories_is_default');
                $table->dropIndex('idx_re_categories_name');
            });
        }
    }
};
