<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name'        => 'Package 1 — Basic Service',
                'description' => 'Essential maintenance for everyday vehicles.',
                'price'       => 120.00,
                'items'       => ['Gasket', 'Engine Oil'],
                'is_active'   => true,
            ],
            [
                'name'        => 'Package 2 — Full Service',
                'description' => 'Complete service including tyre replacement.',
                'price'       => 220.00,
                'items'       => ['Gasket', 'Engine Oil', 'Tyre'],
                'is_active'   => true,
            ],
        ];

        foreach ($packages as $data) {
            Package::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}
