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
        if (!Schema::hasColumn('re_accounts', 'microsite_enabled')) {
            Schema::table('re_accounts', function (Blueprint $table) {
                $table->boolean('microsite_enabled')->default(false);
                $table->string('microsite_slug')->unique()->nullable();
                $table->string('microsite_logo')->nullable();
                $table->string('microsite_banner')->nullable();
                $table->string('microsite_primary_color')->default('#db1d23');
                $table->string('microsite_secondary_color')->default('#161e2d');
                $table->text('microsite_about')->nullable();
                $table->json('microsite_social_links')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('re_accounts', function (Blueprint $table) {
            $table->dropColumn([
                'microsite_enabled',
                'microsite_slug',
                'microsite_logo',
                'microsite_banner',
                'microsite_primary_color',
                'microsite_secondary_color',
                'microsite_about',
                'microsite_social_links',
            ]);
        });
    }
};
