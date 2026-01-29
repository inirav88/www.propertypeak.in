<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('re_packages', function (Blueprint $table) {
            // Package type: builder, agent, owner, addon
            $table->enum('package_type', ['builder', 'agent', 'owner', 'addon'])
                ->default('agent')
                ->after('status');

            // For one-time packages (owner packages)
            $table->integer('duration_days')->nullable()->after('package_type')
                ->comment('Duration in days for one-time packages');

            // Whether package is recurring subscription or one-time
            $table->boolean('is_recurring')->default(true)->after('duration_days');

            // Number of projects allowed (for builder packages)
            $table->integer('number_of_projects')->nullable()->after('number_of_listings')
                ->comment('Number of projects allowed for builder packages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('re_packages', function (Blueprint $table) {
            $table->dropColumn([
                'package_type',
                'duration_days',
                'is_recurring',
                'number_of_projects',
            ]);
        });
    }
};
