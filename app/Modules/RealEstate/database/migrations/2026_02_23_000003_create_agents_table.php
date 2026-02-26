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
        if (Schema::hasTable('agents')) {
            return;
        }

        Schema::create('agents', function (Blueprint $table): void {
            $table->id();

            // Foreign key to users table
            $table->unsignedBigInteger('user_id')->unique();

            // Profile Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('slug', 255)->unique();
            $table->text('bio')->nullable();
            $table->string('tagline')->nullable();

            // Professional Details
            $table->string('designation')->nullable();
            $table->string('license_number')->nullable();
            $table->string('rera_id')->nullable();

            // Contact Information
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('whatsapp', 30)->nullable();
            $table->string('website')->nullable();
            $table->string('office_address')->nullable();

            // Media
            $table->string('avatar')->nullable();
            $table->string('banner_image')->nullable();

            // Social Links (stored as JSON)
            $table->json('social_links')->nullable();

            // Experience & Skills
            $table->integer('experience_years')->default(0);
            $table->json('specializations')->nullable();
            $table->json('languages_spoken')->nullable();
            $table->integer('total_sales')->default(0);
            $table->integer('active_listings')->default(0);

            // Location Coverage
            $table->json('service_areas')->nullable();

            // Work Schedule
            $table->json('working_hours')->nullable();

            // SEO Meta
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraint
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            // Indexes
            $table->index(['first_name', 'last_name']);
            $table->index('rera_id');
            $table->index('experience_years');
            $table->index('total_sales');
            $table->index(['slug', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
