<?php

namespace Database\Seeders\Project;

use Illuminate\Database\Seeder;
use App\Domains\Claim\Models\CostItem;

class CostItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CostItem::create([
            'name' => 'A1',
            'description' => 'Directly Incurred Staff',
            'value' => '0.00',
            'active' => true,
        ]);
        
        CostItem::create([
            'name' => 'A2',
            'description' => 'Consumables',
            'value' => '0.00',
            'active' => true,
        ]);
        
        CostItem::create([
            'name' => 'A3',
            'description' => 'Travel & Subsistence',
            'value' => '0.00',
            'active' => true,
        ]);
    }
}
