<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if properties table exists (for both 'properties' and 're_properties')
        $tableName = Schema::hasTable('re_properties') ? 're_properties' : 'properties';

        if (!Schema::hasTable($tableName)) {
            // Create new properties table if doesn't exist
            Schema::create($tableName, function (Blueprint $table): void {
                $this->createPropertiesTable($table);
            });
        } else {
            // Modify existing table
            Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                $this->addColumnsToPropertiesTable($table, $tableName);
            });
        }
    }

    /**
     * Add columns to properties table.
     */
    private function addColumnsToPropertiesTable(Blueprint $table, string $tableName): void
    {
        // Slug column for URL-friendly property names
        if (!Schema::hasColumn($tableName, 'slug')) {
            $afterColumn = Schema::hasColumn($tableName, 'name') ? 'name' : (Schema::hasColumn($tableName, 'title') ? 'title' : 'id');
            $table->string('slug', 255)->nullable()->after($afterColumn);
        } else {
            // Ensure it is nullable if it exists but was created as NOT NULL previously
            $table->string('slug', 255)->nullable()->change();
        }

        // User relationship - who created the property
        if (!Schema::hasColumn($tableName, 'user_id')) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        }

        // Role of the user who added this property
        if (!Schema::hasColumn($tableName, 'added_by_role')) {
            $table->enum('added_by_role', ['admin', 'developer', 'agent'])
                ->nullable()
                ->after('user_id');
        }

        // Link to developer project (optional)
        if (!Schema::hasColumn($tableName, 'developer_project_id')) {
            $table->unsignedBigInteger('developer_project_id')
                ->nullable()
                ->after('added_by_role');

            $table->foreign('developer_project_id')
                ->references('id')
                ->on('developer_projects')
                ->nullOnDelete();
        }

        // Approval status
        if (!Schema::hasColumn($tableName, 'approval_status')) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('status');
        }

        // Approved by (admin)
        if (!Schema::hasColumn($tableName, 'approved_by')) {
            $table->unsignedBigInteger('approved_by')
                ->nullable()
                ->after('approval_status');

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        }

        // Approved at timestamp
        if (!Schema::hasColumn($tableName, 'approved_at')) {
            $table->timestamp('approved_at')
                ->nullable()
                ->after('approved_by');
        }

        // Indexes - only add if they don't exist
        $this->addIndexIfNotExists($table, $tableName, 'slug');
        $this->addIndexIfNotExists($table, $tableName, 'user_id');
        $this->addIndexIfNotExists($table, $tableName, 'added_by_role');
        $this->addIndexIfNotExists($table, $tableName, 'approval_status');
        $this->addIndexIfNotExists($table, $tableName, 'approved_at');
        $this->addIndexIfNotExists($table, $tableName, 'developer_project_id');
        $this->addIndexIfNotExists($table, $tableName, ['user_id', 'approval_status']);
        $this->addIndexIfNotExists($table, $tableName, ['added_by_role', 'approval_status']);
        $this->addIndexIfNotExists($table, $tableName, ['approval_status', 'approved_at']);
        $this->addIndexIfNotExists($table, $tableName, ['developer_project_id', 'approval_status']);
    }

    /**
     * Create full properties table structure.
     */
    private function createPropertiesTable(Blueprint $table): void
    {
        $table->id();

        // User relationship
        $table->unsignedBigInteger('user_id')->nullable();
        $table->enum('added_by_role', ['admin', 'developer', 'agent'])->nullable();

        // Developer project link
        $table->unsignedBigInteger('developer_project_id')->nullable();

        // Basic Information
        $table->string('name');
        $table->string('slug', 255)->unique();
        $table->text('description')->nullable();

        // Property Type & Status
        $table->enum('type', ['sale', 'rent', 'pg'])->default('sale');
        $table->enum('property_type', ['apartment', 'house', 'villa', 'plot', 'commercial', 'office'])
            ->default('apartment');
        $table->enum('status', ['available', 'sold', 'rented', 'under_construction'])
            ->default('available');

        // Pricing
        $table->decimal('price', 15, 2);
        $table->decimal('price_per_sqft', 15, 2)->nullable();

        // Location
        $table->string('address');
        $table->string('city');
        $table->string('state');
        $table->string('country')->nullable();
        $table->string('zip_code', 20)->nullable();
        $table->decimal('latitude', 10, 8)->nullable();
        $table->decimal('longitude', 11, 8)->nullable();

        // Features
        $table->integer('bedrooms')->nullable();
        $table->integer('bathrooms')->nullable();
        $table->integer('balconies')->nullable()->default(0);
        $table->decimal('carpet_area', 10, 2)->nullable();
        $table->decimal('built_up_area', 10, 2)->nullable();
        $table->decimal('super_built_up_area', 10, 2)->nullable();
        $table->integer('total_floors')->nullable();
        $table->integer('floor_number')->nullable();
        $table->integer('parking_spaces')->nullable()->default(0);
        $table->integer('furnishing_status')->nullable(); // 0: unfurnished, 1: semi, 2: fully

        // Amenities (JSON)
        $table->json('amenities')->nullable();
        $table->json('nearby_facilities')->nullable();

        // Media
        $table->json('images')->nullable();
        $table->string('video_url')->nullable();
        $table->string('virtual_tour_url')->nullable();

        // RERA & Legal
        $table->string('rera_number')->nullable();

        // SEO Meta
        $table->string('meta_title')->nullable();
        $table->text('meta_description')->nullable();

        // Approval System
        $table->enum('approval_status', ['pending', 'approved', 'rejected'])
            ->default('pending');
        $table->unsignedBigInteger('approved_by')->nullable();
        $table->timestamp('approved_at')->nullable();

        // Featured
        $table->boolean('is_featured')->default(false);
        $table->timestamp('featured_until')->nullable();

        // Timestamps
        $table->timestamps();
        $table->softDeletes();

        // Foreign Keys
        $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->nullOnDelete();

        $table->foreign('developer_project_id')
            ->references('id')
            ->on('developer_projects')
            ->nullOnDelete();

        $table->foreign('approved_by')
            ->references('id')
            ->on('users')
            ->nullOnDelete();

        // Indexes
        $table->index('user_id');
        $table->index('added_by_role');
        $table->index('developer_project_id');
        $table->index('status');
        $table->index('city');
        $table->index('state');
        $table->index('approval_status');
        $table->index('approved_at');
        $table->index('is_featured');
        $table->index('rera_number');
        $table->index(['city', 'status', 'approval_status']);
        $table->index(['type', 'status', 'approval_status']);
        $table->index(['user_id', 'approval_status']);
        $table->index(['added_by_role', 'approval_status']);
        $table->index(['price', 'status', 'approval_status']);
        $table->index(['is_featured', 'featured_until', 'approval_status']);
    }

    /**
     * Add index if it doesn't exist.
     */
    private function addIndexIfNotExists(Blueprint $table, string $tableName, array|string $columns): void
    {
        $columns = (array) $columns;
        $indexName = strtolower($tableName . '_' . implode('_', $columns) . '_index');
        
        // Get existing indexes
        $conn = Schema::getConnection();
        $dbName = $conn->getDatabaseName();
        
        $existingIndexes = $conn->select(
            "SELECT INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS 
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ?",
            [$dbName, $tableName, $indexName]
        );

        if (empty($existingIndexes)) {
            $table->index($columns, $indexName);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = Schema::hasTable('re_properties') ? 're_properties' : 'properties';

        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                // Drop foreign keys
                $foreignKeys = [
                    'user_id',
                    'developer_project_id',
                    'approved_by',
                ];

                foreach ($foreignKeys as $key) {
                    if (Schema::hasColumn($tableName, $key)) {
                        try {
                            $table->dropForeign([$key]);
                        } catch (\Exception $e) {
                            // Foreign key might not exist
                        }
                    }
                }

                // Drop columns
                $columns = [
                    'user_id',
                    'added_by_role',
                    'developer_project_id',
                    'approval_status',
                    'approved_by',
                    'approved_at',
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn($tableName, $column)) {
                        try {
                            $table->dropColumn($column);
                        } catch (\Exception $e) {
                            // Column might not exist
                        }
                    }
                }
            });
        }
    }
};
