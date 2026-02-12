<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('re_developer_profiles', function (Blueprint $table): void {
            if (Schema::hasColumn('re_developer_profiles', 'auto_approve')) {
                $table->dropColumn('auto_approve');
            }

            if (Schema::hasColumn('re_developer_profiles', 'requires_approval')) {
                $table->dropColumn('requires_approval');
            }
        });

        Schema::table('re_developer_profiles', function (Blueprint $table): void {
            $table->boolean('auto_approve_changes')->default(false)->after('is_blocked')->index();
            $table->boolean('requires_approval')->nullable()->after('auto_approve_changes')->index();
        });
    }

    public function down(): void
    {
        Schema::table('re_developer_profiles', function (Blueprint $table): void {
            $table->dropColumn(['auto_approve_changes', 'requires_approval']);
        });

        Schema::table('re_developer_profiles', function (Blueprint $table): void {
            $table->boolean('auto_approve')->default(false)->after('is_blocked')->index();
            $table->boolean('requires_approval')->default(true)->after('auto_approve')->index();
        });
    }
};
