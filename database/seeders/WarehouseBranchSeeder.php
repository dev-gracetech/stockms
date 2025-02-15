<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class WarehouseBranchSeeder extends Seeder
{
    public function run()
    {
        // Check if the warehouse branch already exists
        $warehouse = Branch::where('name', 'Warehouse')->first();

        if (!$warehouse) {
            // Create the warehouse branch
            Branch::create([
                'name' => 'Warehouse',
                'location' => 'Main Warehouse Location',
            ]);
        }
    }
}
