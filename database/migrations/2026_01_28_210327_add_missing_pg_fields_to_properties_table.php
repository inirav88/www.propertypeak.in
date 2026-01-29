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
        Schema::table('re_properties', function (Blueprint $table) {
            // Add each column individually with existence check
            if (!Schema::hasColumn('re_properties', 'pg_occupancy_type')) {
                $table->string('pg_occupancy_type', 30)->nullable()->after('pg_category');
            }
            if (!Schema::hasColumn('re_properties', 'total_beds')) {
                $table->integer('total_beds')->nullable()->after('pg_occupancy_type');
            }
            if (!Schema::hasColumn('re_properties', 'available_beds')) {
                $table->integer('available_beds')->nullable()->after('total_beds');
            }
            if (!Schema::hasColumn('re_properties', 'pricing_model')) {
                $table->string('pricing_model', 20)->nullable()->after('price');
            }
            if (!Schema::hasColumn('re_properties', 'price_per_bed')) {
                $table->decimal('price_per_bed', 15, 2)->nullable()->after('pricing_model');
            }
            if (!Schema::hasColumn('re_properties', 'price_per_room')) {
                $table->decimal('price_per_room', 15, 2)->nullable()->after('price_per_bed');
            }
            if (!Schema::hasColumn('re_properties', 'security_deposit')) {
                $table->decimal('security_deposit', 15, 2)->nullable()->after('price_per_room');
            }
            if (!Schema::hasColumn('re_properties', 'maintenance_charges')) {
                $table->decimal('maintenance_charges', 15, 2)->nullable()->after('security_deposit');
            }
            if (!Schema::hasColumn('re_properties', 'notice_period_days')) {
                $table->integer('notice_period_days')->default(30)->after('maintenance_charges');
            }
            if (!Schema::hasColumn('re_properties', 'food_included')) {
                $table->boolean('food_included')->default(false)->after('notice_period_days');
            }
            if (!Schema::hasColumn('re_properties', 'food_type')) {
                $table->string('food_type', 20)->nullable()->after('food_included');
            }
            if (!Schema::hasColumn('re_properties', 'meals_provided')) {
                $table->json('meals_provided')->nullable()->after('food_type');
            }
            if (!Schema::hasColumn('re_properties', 'ac_available')) {
                $table->boolean('ac_available')->default(false)->after('meals_provided');
            }
            if (!Schema::hasColumn('re_properties', 'wifi_included')) {
                $table->boolean('wifi_included')->default(false)->after('ac_available');
            }
            if (!Schema::hasColumn('re_properties', 'laundry_included')) {
                $table->boolean('laundry_included')->default(false)->after('wifi_included');
            }
            if (!Schema::hasColumn('re_properties', 'parking_available')) {
                $table->boolean('parking_available')->default(false)->after('laundry_included');
            }
            if (!Schema::hasColumn('re_properties', 'gender_preference')) {
                $table->string('gender_preference', 20)->nullable()->after('parking_available');
            }
            if (!Schema::hasColumn('re_properties', 'preferred_tenants')) {
                $table->json('preferred_tenants')->nullable()->after('gender_preference');
            }
            if (!Schema::hasColumn('re_properties', 'gate_closing_time')) {
                $table->time('gate_closing_time')->nullable()->after('preferred_tenants');
            }
            if (!Schema::hasColumn('re_properties', 'visitors_allowed')) {
                $table->boolean('visitors_allowed')->default(true)->after('gate_closing_time');
            }
            if (!Schema::hasColumn('re_properties', 'smoking_allowed')) {
                $table->boolean('smoking_allowed')->default(false)->after('visitors_allowed');
            }
            if (!Schema::hasColumn('re_properties', 'drinking_allowed')) {
                $table->boolean('drinking_allowed')->default(false)->after('smoking_allowed');
            }
            if (!Schema::hasColumn('re_properties', 'house_rules')) {
                $table->text('house_rules')->nullable()->after('drinking_allowed');
            }
            if (!Schema::hasColumn('re_properties', 'nearby_landmarks')) {
                $table->text('nearby_landmarks')->nullable()->after('house_rules');
            }
            if (!Schema::hasColumn('re_properties', 'furnishing_details')) {
                $table->json('furnishing_details')->nullable()->after('nearby_landmarks');
            }
            if (!Schema::hasColumn('re_properties', 'virtual_tour_url')) {
                $table->string('virtual_tour_url')->nullable()->after('furnishing_details');
            }
            if (!Schema::hasColumn('re_properties', 'instant_booking')) {
                $table->boolean('instant_booking')->default(false)->after('virtual_tour_url');
            }
            if (!Schema::hasColumn('re_properties', 'verified_pg')) {
                $table->boolean('verified_pg')->default(false)->after('instant_booking');
            }
            if (!Schema::hasColumn('re_properties', 'owner_stays')) {
                $table->boolean('owner_stays')->default(false)->after('verified_pg');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('re_properties', function (Blueprint $table) {
            $columns = [
                'pg_occupancy_type',
                'total_beds',
                'available_beds',
                'pricing_model',
                'price_per_bed',
                'price_per_room',
                'security_deposit',
                'maintenance_charges',
                'notice_period_days',
                'food_included',
                'food_type',
                'meals_provided',
                'ac_available',
                'wifi_included',
                'laundry_included',
                'parking_available',
                'gender_preference',
                'preferred_tenants',
                'gate_closing_time',
                'visitors_allowed',
                'smoking_allowed',
                'drinking_allowed',
                'house_rules',
                'nearby_landmarks',
                'furnishing_details',
                'virtual_tour_url',
                'instant_booking',
                'verified_pg',
                'owner_stays'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('re_properties', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
