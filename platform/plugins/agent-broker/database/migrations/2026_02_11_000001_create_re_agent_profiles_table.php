<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('re_agent_profiles', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('account_id')->unique();
            $table->string('slug')->unique();
            $table->text('bio')->nullable();
            $table->string('designation')->nullable();
            $table->string('photo')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->string('website')->nullable();
            $table->json('social_links')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_verified')->default(false)->index();
            $table->boolean('is_blocked')->default(false)->index();
            $table->boolean('requires_approval')->default(true)->index();
            $table->boolean('auto_approve')->default(false)->index();
            $table->string('status', 60)->default('draft')->index();
            $table->timestamp('approved_at')->nullable()->index();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('account_id')
                ->references('id')
                ->on('re_accounts')
                ->cascadeOnDelete();

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index(['status', 'is_featured']);
            $table->index(['status', 'is_verified']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('re_agent_profiles');
    }
};
