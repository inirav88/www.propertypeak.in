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
        if (Schema::hasTable('developer_projects')) {
            return;
        }

        Schema::create('developer_projects', function (Blueprint $table): void {
            $table->id();

            // Foreign key to developers table
            $table->unsignedBigInteger('developer_id');

            // Basic Information
            $table->string('name');
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();

            // Project Type & Status
            $table->enum('type', ['residential', 'commercial', 'mixed_use', 'industrial', 'retail'])
                ->default('residential');
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'sold_out'])
                ->default('upcoming');

            // Location
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // RERA & Legal
            $table->string('rera_number')->nullable();
            $table->string('legal_clearance')->nullable();

            // Dates
            $table->date('launch_date')->nullable();
            $table->date('possession_date')->nullable();
            $table->date('completion_date')->nullable();

            // Configuration Details
            $table->json('unit_configurations')->nullable();
            $table->integer('total_units')->nullable();
            $table->integer('total_towers')->nullable();
            $table->integer('total_floors')->nullable();
            $table->decimal('total_area', 15, 2)->nullable();
            $table->decimal('price_starting_from', 15, 2)->nullable();
            $table->decimal('price_per_sqft', 15, 2)->nullable();

            // Amenities & Features
            $table->json('amenities')->nullable();
            $table->json('specifications')->nullable();
            $table->json('nearby_facilities')->nullable();

            // Media (stored as JSON arrays)
            $table->json('gallery_images')->nullable();
            $table->json('floor_plans')->nullable();
            $table->string('brochure')->nullable();
            $table->string('video_url')->nullable();
            $table->string('virtual_tour_url')->nullable();

            // SEO Meta
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // Approval & Visibility
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                ->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('featured_until')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraints
            $table->foreign('developer_id')
                ->references('id')
                ->on('developers')
                ->cascadeOnDelete();

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            // Indexes
            $table->index('status');
            $table->index('city');
            $table->index('state');
            $table->index('rera_number');
            $table->index('launch_date');
            $table->index('possession_date');
            $table->index('approval_status');
            $table->index('approved_at');
            $table->index('is_featured');
            $table->index(['developer_id', 'status']);
            $table->index(['developer_id', 'approval_status']);
            $table->index(['status', 'approval_status']);
            $table->index(['city', 'status']);
            $table->index(['launch_date', 'status']);
            $table->index(['is_featured', 'featured_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developer_projects');
    }
};
