<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('re_consults', function (Blueprint $table): void {
            if (! Schema::hasColumn('re_consults', 'pipeline_stage')) {
                $table->string('pipeline_stage')->default('new')->index()->after('status');
            }

            if (! Schema::hasColumn('re_consults', 'assigned_to')) {
                $table->unsignedBigInteger('assigned_to')->nullable()->index()->after('notes');
                $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('re_consults', 'follow_up_at')) {
                $table->timestamp('follow_up_at')->nullable()->index()->after('assigned_to');
            }

            if (! Schema::hasColumn('re_consults', 'contacted_at')) {
                $table->timestamp('contacted_at')->nullable()->index()->after('follow_up_at');
            }

            if (! Schema::hasColumn('re_consults', 'internal_notes')) {
                $table->text('internal_notes')->nullable()->after('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('re_consults', function (Blueprint $table): void {
            if (Schema::hasColumn('re_consults', 'pipeline_stage')) {
                $table->dropColumn('pipeline_stage');
            }

            if (Schema::hasColumn('re_consults', 'follow_up_at')) {
                $table->dropColumn('follow_up_at');
            }

            if (Schema::hasColumn('re_consults', 'internal_notes')) {
                $table->dropColumn('internal_notes');
            }
        });
    }
};
