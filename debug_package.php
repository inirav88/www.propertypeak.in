<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$account = \Botble\RealEstate\Models\Account::first();

if (!$account) {
    echo "No accounts found.\n";
    exit;
}

echo "Account ID: {$account->id}\n";
$package = $account->package;

echo "Package Type: " . gettype($package) . "\n";
if (is_object($package)) {
    echo "Package Class: " . get_class($package) . "\n";

    if ($package instanceof \Illuminate\Support\Collection) {
        echo "It is a Collection! Count: " . $package->count() . "\n";
        echo "First item: " . (json_encode($package->first()) ?: 'null') . "\n";
    } elseif ($package instanceof \Botble\RealEstate\Models\Package) {
        echo "It is a Package Model!\n";
        echo "Microsite Enabled: " . ($package->microsite_enabled ? 'YES' : 'NO') . "\n";
    } else {
        echo "Unknown object type.\n";
    }
} else {
    echo "Package is NULL or not an object.\n";
}
