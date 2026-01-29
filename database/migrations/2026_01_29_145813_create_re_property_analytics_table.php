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
        if (!Schema::hasTable('re_property_analytics')) {
            Schema::create('re_property_analytics', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('property_id');
                $table->enum('event_type', ['view', 'contact', 'phone_click', 'email_click', 'whatsapp_click']);
                $table->string('user_ip')->nullable();
                $table->text('user_agent')->nullable();
                $table->string('referrer')->nullable();
                $table->timestamps();

                $table->foreign('property_id')->references('id')->on('re_properties')->onDelete('cascade');
                $table->index(['property_id', 'event_type']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('re_property_analytics');
    }
};
