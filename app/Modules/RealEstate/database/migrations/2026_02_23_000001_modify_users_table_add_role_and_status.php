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
        Schema::table('users', function (Blueprint $table): void {
            // Role enum: admin, developer, agent
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'developer', 'agent'])
                    ->default('developer')
                    ->after('password');
            }

            // Status enum: pending, approved, rejected, suspended
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])
                    ->default('pending')
                    ->after('role');
            }

            // Approval timestamps and references
            if (!Schema::hasColumn('users', 'approved_at')) {
                $table->timestamp('approved_at')
                    ->nullable()
                    ->after('status');
            }

            if (!Schema::hasColumn('users', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')
                    ->nullable()
                    ->after('approved_at');

                $table->foreign('approved_by')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            }

            // SEO-friendly unique slug
            if (!Schema::hasColumn('users', 'slug')) {
                $table->string('slug', 255)
                    ->unique()
                    ->after('approved_by');
            }
        });

        // Add indexes separately after columns are created
        Schema::table('users', function (Blueprint $table): void {
            // Check if indexes exist before creating
            $this->createIndexIfNotExists($table, 'users', 'role');
            $this->createIndexIfNotExists($table, 'users', 'status');
            $this->createIndexIfNotExists($table, 'users', 'slug');
            $this->createIndexIfNotExists($table, 'users', 'approved_at');
            
            // Composite indexes
            $this->createIndexIfNotExists($table, 'users', ['role', 'status']);
            $this->createIndexIfNotExists($table, 'users', ['status', 'approved_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            // Drop foreign keys first
            try {
                $table->dropForeign(['approved_by']);
            } catch (\Exception $e) {
                // Foreign key might not exist
            }

            // Drop columns (indexes will be dropped automatically with columns)
            $columns = ['role', 'status', 'approved_at', 'approved_by', 'slug'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    try {
                        $table->dropColumn($column);
                    } catch (\Exception $e) {
                        // Column might not exist
                    }
                }
            }
        });
    }

    /**
     * Create index if it doesn't exist.
     */
    private function createIndexIfNotExists(Blueprint $table, string $tableName, array|string $columns): void
    {
        $columns = (array) $columns;
        $indexName = $tableName . '_' . implode('_', $columns) . '_index';
        
        // Check if index exists
        $indexes = Schema::getIndexes($tableName);
        foreach ($indexes as $index) {
            if ($index['name'] === $indexName) {
                return; // Index already exists
            }
        }
        
        $table->index($columns);
    }
};
