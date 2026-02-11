<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('re_developer_project_media', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('developer_project_id');
            $table->string('type', 60)->index();
            $table->string('file');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('developer_project_id')
                ->references('id')
                ->on('re_developer_projects')
                ->cascadeOnDelete();

            $table->index(['developer_project_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('re_developer_project_media');
    }
};
