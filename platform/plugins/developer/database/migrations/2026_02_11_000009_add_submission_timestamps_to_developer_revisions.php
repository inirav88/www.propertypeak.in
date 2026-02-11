<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('re_developer_profile_revisions', function (Blueprint $table): void {
            $table->timestamp('submitted_at')->nullable()->after('submitted_by')->index();
            $table->timestamp('approved_at')->nullable()->after('submitted_at')->index();
        });

        Schema::table('re_developer_project_revisions', function (Blueprint $table): void {
            $table->timestamp('submitted_at')->nullable()->after('submitted_by')->index();
            $table->timestamp('approved_at')->nullable()->after('submitted_at')->index();
        });
    }

    public function down(): void
    {
        Schema::table('re_developer_profile_revisions', function (Blueprint $table): void {
            $table->dropColumn(['submitted_at', 'approved_at']);
        });

        Schema::table('re_developer_project_revisions', function (Blueprint $table): void {
            $table->dropColumn(['submitted_at', 'approved_at']);
        });
    }
};
