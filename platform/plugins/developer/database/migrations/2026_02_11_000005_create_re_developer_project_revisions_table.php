<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('re_developer_project_revisions', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('entity_id')->index();
            $table->string('entity_type', 120)->index();
            $table->json('payload');
            $table->string('status', 60)->default('pending')->index();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable()->index();
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('submitted_by');
            $table->timestamps();

            $table->foreign('entity_id')
                ->references('id')
                ->on('re_developer_projects')
                ->cascadeOnDelete();

            $table->foreign('reviewed_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('submitted_by')
                ->references('id')
                ->on('re_accounts')
                ->cascadeOnDelete();

            $table->index(['entity_id', 'status']);
            $table->index(['submitted_by', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('re_developer_project_revisions');
    }
};
