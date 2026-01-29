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
        Schema::table('re_accounts', function (Blueprint $table) {
            $table->string('microsite_website')->nullable();
            $table->string('microsite_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('re_accounts', function (Blueprint $table) {
            $table->dropColumn(['microsite_website', 'microsite_address']);
        });
    }
};
