<?php

use Illuminate\Support\Facades\DB;

// Update the property type to 'pg'
DB::table('re_properties')
    ->where('name', 'Silver Stone PG for Girls')
    ->update(['type' => 'pg']);

echo "Property type updated to 'pg' successfully!\n";
