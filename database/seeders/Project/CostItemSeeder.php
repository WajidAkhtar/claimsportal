<?php

namespace Database\Seeders\Project;

use Illuminate\Database\Seeder;

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
            'value' => '',
            'active' => true,
        ]);
        
        CostItem::create([
            'name' => 'A2',
            'description' => 'Consumables',
            'value' => '',
            'active' => true,
        ]);
        
        CostItem::create([
            'name' => 'A3',
            'description' => 'Travel & Subsistence',
            'value' => '',
            'active' => true,
        ]);
    }
}
