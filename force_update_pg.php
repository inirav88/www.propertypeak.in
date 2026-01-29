<?php

use Botble\RealEstate\Models\Property;

$id = 63;
$property = Property::find($id);

if ($property) {
    echo "Current Type: " . $property->type . "\n";
    $property->type = 'pg';
    $property->save();
    echo "New Type: " . $property->type . "\n";
    echo "Saved successfully.\n";
} else {
    echo "Property ID $id not found.\n";
}
