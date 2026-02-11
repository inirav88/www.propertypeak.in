<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('re_consults', function (Blueprint $table): void {
            if (! Schema::hasColumn('re_consults', 'reference_id')) {
                $table->unsignedBigInteger('reference_id')->nullable()->index()->after('property_id');
            }

            if (! Schema::hasColumn('re_consults', 'reference_type')) {
                $table->string('reference_type')->nullable()->index()->after('reference_id');
            }

            if (! Schema::hasColumn('re_consults', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable()->index()->after('reference_type');

                $table->foreign('project_id')
                    ->references('id')
                    ->on('re_developer_projects')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('re_consults', function (Blueprint $table): void {

            if (Schema::hasColumn('re_consults', 'reference_type')) {
                $table->dropColumn('reference_type');
            }

            if (Schema::hasColumn('re_consults', 'reference_id')) {
                $table->dropColumn('reference_id');
            }
        });
    }
};
