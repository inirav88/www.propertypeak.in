<?php

use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Enums\PropertyTypeEnum;

$id = 63;
$property = Property::find($id);

if ($property) {
    echo "Type Value: " . var_export($property->type, true) . "\n";
    echo "Is Object? " . (is_object($property->type) ? 'YES' : 'NO') . "\n";

    if (is_object($property->type)) {
        echo "Class: " . get_class($property->type) . "\n";
        echo "Value Property: " . ($property->type->value ?? 'N/A') . "\n";
        echo "GetValue Method: " . (method_exists($property->type, 'getValue') ? $property->type->getValue() : 'N/A') . "\n";
    }

    echo "Constant PG: " . PropertyTypeEnum::PG . "\n";

    echo "Comparison (=== 'pg'): " . ($property->type === 'pg' ? 'TRUE' : 'FALSE') . "\n";
    echo "Comparison (== 'pg'): " . ($property->type == 'pg' ? 'TRUE' : 'FALSE') . "\n";
    echo "Comparison (=== Constant): " . ($property->type === PropertyTypeEnum::PG ? 'TRUE' : 'FALSE') . "\n";
}
