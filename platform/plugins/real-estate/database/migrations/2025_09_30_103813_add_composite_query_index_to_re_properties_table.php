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
        Schema::table('re_properties', function (Blueprint $table): void {
            // Add covering index for the most common query pattern
            // This index covers the active() scope conditions plus sorting
            $table->index(
                ['moderation_status', 'status', 'expire_date', 'never_expired', 'is_featured', 'created_at'],
                'idx_re_properties_active_featured_sort'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('re_properties', function (Blueprint $table): void {
            $table->dropIndex('idx_re_properties_active_featured_sort');
        });
    }
};
