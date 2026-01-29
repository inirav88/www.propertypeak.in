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
        if (!Schema::hasTable('re_saved_searches')) {
            Schema::create('re_saved_searches', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('account_id');
                $table->string('name');
                $table->json('search_criteria');
                $table->enum('alert_frequency', ['instant', 'daily', 'weekly'])->default('daily');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('account_id')->references('id')->on('re_accounts')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('re_saved_searches');
    }
};
