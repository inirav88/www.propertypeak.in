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
        if (Schema::hasTable('developers')) {
            return;
        }

        Schema::create('developers', function (Blueprint $table): void {
            $table->id();

            // Foreign key to users table
            $table->unsignedBigInteger('user_id')->unique();

            // Company/Profile Information
            $table->string('company_name');
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->string('tagline')->nullable();

            // Contact Information
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('website')->nullable();
            $table->string('office_address')->nullable();

            // Media
            $table->string('logo')->nullable();
            $table->string('banner_image')->nullable();

            // Social Links (stored as JSON)
            $table->json('social_links')->nullable();

            // Business Details
            $table->string('registration_number')->nullable();
            $table->string('rera_id')->nullable();
            $table->year('established_year')->nullable();
            $table->integer('total_projects')->default(0);
            $table->integer('completed_projects')->default(0);
            $table->integer('ongoing_projects')->default(0);

            // Team/Employees
            $table->integer('employee_count')->nullable();

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
            $table->index('company_name');
            $table->index('rera_id');
            $table->index('established_year');
            $table->index('total_projects');
            $table->index('completed_projects');
            $table->index(['slug', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developers');
    }
};
