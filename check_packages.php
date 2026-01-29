<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$packages = \Botble\RealEstate\Models\Package::all();

echo "Found " . $packages->count() . " packages:\n";
foreach ($packages as $p) {
    $features = $p->features;
    if (is_string($features)) {
        $features = json_decode($features, true);
    }
    // Check if simple array or key-value
    $hasMicrosite = false;
    if (is_array($features)) {
        if (isset($features['microsite']))
            $hasMicrosite = true;
        if (in_array('microsite', $features))
            $hasMicrosite = true;
        // Check array of arrays struct if that's how it's stored
        foreach ($features as $f) {
            if (is_array($f) && isset($f['key']) && $f['key'] === 'microsite')
                $hasMicrosite = true;
            if (is_string($f) && $f === 'microsite')
                $hasMicrosite = true;
        }
    }

    echo "ID: {$p->id} | Name: {$p->name} | Has Microsite: " . ($hasMicrosite ? 'YES' : 'NO') . "\n";
}
