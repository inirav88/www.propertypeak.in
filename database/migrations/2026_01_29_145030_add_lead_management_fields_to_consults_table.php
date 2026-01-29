<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('re_consults', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('content');
            $table->unsignedBigInteger('assigned_to')->nullable()->after('notes');
            $table->integer('score')->default(0)->after('assigned_to');
            $table->timestamp('follow_up_date')->nullable()->after('score');
            $table->timestamp('contacted_at')->nullable()->after('follow_up_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('re_consults', function (Blueprint $table) {
            $table->dropColumn(['notes', 'assigned_to', 'score', 'follow_up_date', 'contacted_at']);
        });
    }
};
