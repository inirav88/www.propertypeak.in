<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('re_developer_leads', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('developer_profile_id');
            $table->unsignedBigInteger('developer_project_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable()->index();
            $table->string('phone', 30)->nullable()->index();
            $table->text('message')->nullable();
            $table->string('source', 120)->default('website')->index();
            $table->string('status', 60)->default('new')->index();
            $table->json('metadata')->nullable();
            $table->timestamp('contacted_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('developer_profile_id')
                ->references('id')
                ->on('re_developer_profiles')
                ->cascadeOnDelete();

            $table->foreign('developer_project_id')
                ->references('id')
                ->on('re_developer_projects')
                ->nullOnDelete();

            $table->index(['developer_profile_id', 'status']);
            $table->index(['developer_profile_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('re_developer_leads');
    }
};
