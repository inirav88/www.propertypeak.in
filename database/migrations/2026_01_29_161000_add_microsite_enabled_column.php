<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('re_packages', 'microsite_enabled')) {
            Schema::table('re_packages', function (Blueprint $table) {
                $table->boolean('microsite_enabled')->default(true);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('re_packages', 'microsite_enabled')) {
            Schema::table('re_packages', function (Blueprint $table) {
                $table->dropColumn('microsite_enabled');
            });
        }
    }
};
