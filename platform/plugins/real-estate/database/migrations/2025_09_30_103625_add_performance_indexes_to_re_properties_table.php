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
            // Add index for type column (used in filtering)
            $table->index('type', 'idx_re_properties_type');

            // Add index for never_expired column (used with expire_date in active() scope)
            $table->index('never_expired', 'idx_re_properties_never_expired');

            // Add composite index for moderation_status and status (frequently queried together)
            $table->index(['moderation_status', 'status'], 'idx_re_properties_mod_status');

            // Add index for price column (used in price filtering)
            $table->index('price', 'idx_re_properties_price');

            // Add index for square column (used in square footage filtering)
            $table->index('square', 'idx_re_properties_square');

            // Add indexes for bedroom, bathroom, floor filtering
            $table->index('number_bedroom', 'idx_re_properties_bedroom');
            $table->index('number_bathroom', 'idx_re_properties_bathroom');
            $table->index('number_floor', 'idx_re_properties_floor');

            // Add index for project_id (used in project filtering)
            $table->index('project_id', 'idx_re_properties_project_id');

            // Add composite index for author filtering
            $table->index(['author_id', 'author_type'], 'idx_re_properties_author');

            // Add index for country_id (foreign key)
            $table->index('country_id', 'idx_re_properties_country_id');

            // Add index for currency_id (foreign key)
            $table->index('currency_id', 'idx_re_properties_currency_id');
        });

        // Add index to re_property_features for better join performance
        if (Schema::hasTable('re_property_features')) {
            Schema::table('re_property_features', function (Blueprint $table): void {
                $table->index('property_id', 'idx_property_features_property_id');
                $table->index('feature_id', 'idx_property_features_feature_id');
            });
        }

        // Add index to re_property_categories for better join performance
        if (Schema::hasTable('re_property_categories')) {
            Schema::table('re_property_categories', function (Blueprint $table): void {
                $table->index('property_id', 'idx_property_categories_property_id');
                $table->index('category_id', 'idx_property_categories_category_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('re_properties', function (Blueprint $table): void {
            $table->dropIndex('idx_re_properties_type');
            $table->dropIndex('idx_re_properties_never_expired');
            $table->dropIndex('idx_re_properties_mod_status');
            $table->dropIndex('idx_re_properties_price');
            $table->dropIndex('idx_re_properties_square');
            $table->dropIndex('idx_re_properties_bedroom');
            $table->dropIndex('idx_re_properties_bathroom');
            $table->dropIndex('idx_re_properties_floor');
            $table->dropIndex('idx_re_properties_project_id');
            $table->dropIndex('idx_re_properties_author');
            $table->dropIndex('idx_re_properties_country_id');
            $table->dropIndex('idx_re_properties_currency_id');
        });

        if (Schema::hasTable('re_property_features')) {
            Schema::table('re_property_features', function (Blueprint $table): void {
                $table->dropIndex('idx_property_features_property_id');
                $table->dropIndex('idx_property_features_feature_id');
            });
        }

        if (Schema::hasTable('re_property_categories')) {
            Schema::table('re_property_categories', function (Blueprint $table): void {
                $table->dropIndex('idx_property_categories_property_id');
                $table->dropIndex('idx_property_categories_category_id');
            });
        }
    }
};
