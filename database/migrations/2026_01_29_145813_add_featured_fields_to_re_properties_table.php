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
        if (!Schema::hasColumn('re_properties', 'is_featured')) {
            Schema::table('re_properties', function (Blueprint $table) {
                $table->boolean('is_featured')->default(false);
                $table->timestamp('featured_until')->nullable();
                $table->integer('priority_score')->default(0);
            });
        }
    }

    public function down(): void
    {
        Schema::table('re_properties', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'featured_until', 'priority_score']);
        });
    }
};
