<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('re_properties', function (Blueprint $table) {
            // Check if columns already exist to prevent duplicate errors
            if (!Schema::hasColumn('re_properties', 'pg_category')) {
                // PG Basic Information
                $table->string('pg_category', 50)->nullable()->after('type');
                $table->string('pg_occupancy_type', 30)->nullable()->after('pg_category');
                $table->integer('total_beds')->nullable()->after('pg_occupancy_type');
                $table->integer('available_beds')->nullable()->after('total_beds');

                // Pricing Model
                $table->string('pricing_model', 20)->nullable()->after('price');
                $table->decimal('price_per_bed', 15, 2)->nullable()->after('pricing_model');
                $table->decimal('price_per_room', 15, 2)->nullable()->after('price_per_bed');
                $table->decimal('security_deposit', 15, 2)->nullable()->after('price_per_room');
                $table->decimal('maintenance_charges', 15, 2)->nullable()->after('security_deposit');
                $table->integer('notice_period_days')->default(30)->after('maintenance_charges');

                // Food & Amenities
                $table->boolean('food_included')->default(false)->after('notice_period_days');
                $table->string('food_type', 20)->nullable()->after('food_included');
                $table->json('meals_provided')->nullable()->after('food_type');
                $table->boolean('ac_available')->default(false)->after('meals_provided');
                $table->boolean('wifi_included')->default(false)->after('ac_available');
                $table->boolean('laundry_included')->default(false)->after('wifi_included');
                $table->boolean('parking_available')->default(false)->after('laundry_included');

                // Rules & Preferences
                $table->string('gender_preference', 20)->nullable()->after('parking_available');
                $table->json('preferred_tenants')->nullable()->after('gender_preference');
                $table->time('gate_closing_time')->nullable()->after('preferred_tenants');
                $table->boolean('visitors_allowed')->default(true)->after('gate_closing_time');
                $table->boolean('smoking_allowed')->default(false)->after('visitors_allowed');
                $table->boolean('drinking_allowed')->default(false)->after('smoking_allowed');
                $table->text('house_rules')->nullable()->after('drinking_allowed');

                // Additional Information
                $table->text('nearby_landmarks')->nullable()->after('house_rules');
                $table->json('furnishing_details')->nullable()->after('nearby_landmarks');

                // Modern Features
                $table->string('virtual_tour_url')->nullable()->after('furnishing_details');
                $table->boolean('instant_booking')->default(false)->after('virtual_tour_url');
                $table->boolean('verified_pg')->default(false)->after('instant_booking');
                $table->boolean('owner_stays')->default(false)->after('verified_pg');
            }
        });
    }

    public function down(): void
    {
        Schema::table('re_properties', function (Blueprint $table) {
            $table->dropColumn([
                'pg_category',
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
                'owner_stays',
            ]);
        });
    }
};
