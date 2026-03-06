<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignPackageTypesSeeder extends Seeder
{
    public function run(): void
    {
        $mapByName = [
            'Owner Free' => 'owner',
            'Owner Basic' => 'owner',
            'Owner Premium' => 'owner',
            'Agent Basic' => 'agent',
            'Agent Professional' => 'agent',
            'Agent Premium' => 'agent',
            'Builder Starter' => 'builder',
            'Builder Professional' => 'builder',
            'Builder Enterprise' => 'builder',
        ];

        foreach ($mapByName as $name => $type) {
            DB::table('re_packages')->where('name', $name)->update(['package_type' => $type]);
        }

        DB::table('re_packages')
            ->where(function ($query) {
                $query
                    ->where('name', 'like', '%addon%')
                    ->orWhere('name', 'like', '%add-on%')
                    ->orWhere('name', 'like', '%add on%');
            })
            ->update(['package_type' => 'addon']);
    }
}
