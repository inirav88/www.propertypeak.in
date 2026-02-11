<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('re_developer_projects', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('developer_profile_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('rera_number', 120)->nullable()->index();
            $table->date('possession_date')->nullable()->index();
            $table->string('project_status', 60)->default('upcoming')->index();
            $table->json('amenities')->nullable();
            $table->json('gallery')->nullable();
            $table->json('floor_plans')->nullable();
            $table->string('brochure')->nullable();
            $table->json('pricing_details')->nullable();
            $table->json('unit_configurations')->nullable();
            $table->json('highlights')->nullable();
            $table->text('about_developer')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->string('visibility_status', 60)->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('approval_status', 60)->default('pending')->index();
            $table->timestamp('approved_at')->nullable()->index();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('developer_profile_id')
                ->references('id')
                ->on('re_developer_profiles')
                ->cascadeOnDelete();

            $table->foreign('project_id')
                ->references('id')
                ->on('re_projects')
                ->nullOnDelete();

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index(['developer_profile_id', 'project_status']);
            $table->index(['developer_profile_id', 'approval_status']);
            $table->index(['developer_profile_id', 'visibility_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('re_developer_projects');
    }
};
