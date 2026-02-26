<?php
/**
 * Custom Changes Restoration Script
 * 
 * Run this if your UI customizations get overwritten during updates
 * Usage: php restore-custom-changes.php
 */

echo "====================================\n";
echo "Custom UI Changes Restoration Tool\n";
echo "====================================\n\n";

// Files that contain custom changes
$customFiles = [
    [
        'path' => 'app/Modules/RealEstate/resources/views/frontend/properties/show.blade.php',
        'description' => 'Property detail page - Similar Properties section',
    ],
    [
        'path' => 'app/Modules/RealEstate/resources/views/frontend/partials/property-card.blade.php',
        'description' => 'Property card component',
    ],
];

echo "Checking custom files...\n\n";

$allExist = true;
foreach ($customFiles as $file) {
    $exists = file_exists($file['path']);
    $status = $exists ? '✓' : '✗';
    echo "$status {$file['path']}\n";
    echo "  Description: {$file['description']}\n";
    echo "  Status: " . ($exists ? "EXISTS" : "MISSING") . "\n\n";
    
    if (!$exists) {
        $allExist = false;
    }
}

if (!$allExist) {
    echo "⚠️  WARNING: Some files are missing!\n";
    echo "You may need to restore from git backup.\n\n";
    
    echo "To restore from git, run:\n";
    foreach ($customFiles as $file) {
        if (!file_exists($file['path'])) {
            echo "  git checkout HEAD -- {$file['path']}\n";
        }
    }
    echo "\n";
}

// Clear view cache
echo "Clearing view cache...\n";
$artisanPath = 'artisan';
if (file_exists($artisanPath)) {
    passthru('php ' . $artisanPath . ' view:clear 2>&1', $returnCode);
    if ($returnCode === 0) {
        echo "✓ View cache cleared successfully\n";
    } else {
        echo "✗ Failed to clear view cache (exit code: $returnCode)\n";
    }
} else {
    echo "✗ Artisan not found at: $artisanPath\n";
}

echo "\n====================================\n";
echo "Done!\n";
echo "====================================\n";

// Check if we're in web context
if (php_sapi_name() !== 'cli') {
    echo "\n<script>
        setTimeout(function() {
            window.location.href = '/properties/siddharth-elite-luxurious-3bhk-apartment';
        }, 2000);
    </script>";
    echo "\n<p>Redirecting to property page...</p>";
}
