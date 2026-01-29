<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$packages = \Botble\RealEstate\Models\Package::all();

echo "Checking " . $packages->count() . " packages:\n";
foreach ($packages as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Microsite Enabled: " . ($p->microsite_enabled ? 'YES' : 'NO') . "\n";

    // Force enable if disabled to fix user issue immediately
    if (!$p->microsite_enabled) {
        $p->microsite_enabled = true;
        $p->save();
        echo "   -> UPDATED to YES\n";
    }
}
