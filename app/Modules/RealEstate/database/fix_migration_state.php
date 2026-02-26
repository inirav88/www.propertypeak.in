<?php

/**
 * Run this script to fix migration state issues
 * 
 * Usage: php app/Modules/RealEstate/database/fix_migration_state.php
 */

require __DIR__ . '/../../../../vendor/autoload';

$app = require_once __DIR__ . '/../../../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Fixing migration state...\n\n";

// 1. Remove migration entries
echo "1. Removing migration entries...\n";
DB::table('migrations')
    ->where('migration', 'like', '2026_02_23_%')
    ->delete();
echo "   Done.\n\n";

// 2. Remove columns from users table if they exist
echo "2. Cleaning up users table...\n";
$usersColumns = Schema::getColumnListing('users');
$columnsToRemove = ['role', 'status', 'approved_at', 'approved_by', 'slug'];

foreach ($columnsToRemove as $column) {
    if (in_array($column, $usersColumns)) {
        try {
            // Try to drop foreign key first
            if ($column === 'approved_by') {
                try {
                    DB::statement('ALTER TABLE users DROP FOREIGN KEY users_approved_by_foreign');
                } catch (Exception $e) {
                    // Ignore error
                }
            }
            
            Schema::table('users', function ($table) use ($column) {
                $table->dropColumn($column);
            });
            echo "   Dropped column: $column\n";
        } catch (Exception $e) {
            echo "   Could not drop column $column: " . $e->getMessage() . "\n";
        }
    }
}
echo "   Done.\n\n";

// 3. Drop tables if they exist
echo "3. Dropping tables if they exist...\n";
$tables = ['developer_projects', 'agents', 'developers'];
foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        Schema::drop($table);
        echo "   Dropped table: $table\n";
    }
}
echo "   Done.\n\n";

// 4. Clean up re_properties columns
echo "4. Cleaning up properties table...\n";
$propertiesTable = Schema::hasTable('re_properties') ? 're_properties' : 'properties';

if (Schema::hasTable($propertiesTable)) {
    $propertyColumns = Schema::getColumnListing($propertiesTable);
    $columnsToRemove = ['user_id', 'added_by_role', 'developer_project_id', 'approval_status', 'approved_by', 'approved_at'];
    
    foreach ($columnsToRemove as $column) {
        if (in_array($column, $propertyColumns)) {
            try {
                // Try to drop foreign keys first
                if (in_array($column, ['user_id', 'developer_project_id', 'approved_by'])) {
                    try {
                        $fkName = "{$propertiesTable}_{$column}_foreign";
                        DB::statement("ALTER TABLE {$propertiesTable} DROP FOREIGN KEY {$fkName}");
                    } catch (Exception $e) {
                        // Ignore error
                    }
                }
                
                Schema::table($propertiesTable, function ($table) use ($column) {
                    $table->dropColumn($column);
                });
                echo "   Dropped column: $column\n";
            } catch (Exception $e) {
                echo "   Could not drop column $column: " . $e->getMessage() . "\n";
            }
        }
    }
}
echo "   Done.\n\n";

echo "Migration state cleaned up successfully!\n";
echo "Now you can run: php artisan migrate\n";
