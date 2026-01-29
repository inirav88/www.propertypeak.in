<?php

use Botble\RealEstate\Models\Property;

$property = Property::latest()->first();

if ($property) {
    echo "Property ID: " . $property->id . "\n";
    echo "Property Name: " . $property->name . "\n";
    echo "Property Type: " . $property->type . "\n";
    echo "PG Category: " . ($property->pg_category ?? 'NULL') . "\n";
    echo "Pricing Model: " . ($property->pricing_model ?? 'NULL') . "\n";
    echo "Price Per Bed: " . ($property->price_per_bed ?? 'NULL') . "\n";
    echo "\nType Check: " . ($property->type === 'pg' ? 'TRUE' : 'FALSE') . "\n";
} else {
    echo "No properties found\n";
}
