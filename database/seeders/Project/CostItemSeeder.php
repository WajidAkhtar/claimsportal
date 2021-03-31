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
            'description' => 'Directly Incurred: Staff',
            'active' => true,
            'is_system_generated' => true,
        ]);
        
        CostItem::create([
            'name' => 'A2',
            'description' => 'Directly Incurred: Travel & Subsistence',
            'active' => true,
            'is_system_generated' => true,
        ]);
        
        CostItem::create([
            'name' => 'A3',
            'description' => 'Directly Incurred: Equipment',
            'active' => true,
            'is_system_generated' => true,
        ]);

        CostItem::create([
            'name' => 'A4',
            'description' => 'Directly Incurred: Consumables',
            'active' => true,
            'is_system_generated' => true,
        ]);

        CostItem::create([
            'name' => 'A5',
            'description' => 'Directly Allocated: Investigators',
            'active' => true,
            'is_system_generated' => true,
        ]);

        CostItem::create([
            'name' => 'A6',
            'description' => 'Directly Allocated: Estates',
            'active' => true,
            'is_system_generated' => true,
        ]);

        CostItem::create([
            'name' => 'A7',
            'description' => 'Directly Allocated: Other Costs',
            'active' => true,
            'is_system_generated' => true,
        ]);

        CostItem::create([
            'name' => 'A8',
            'description' => 'Indirect Costs',
            'active' => true,
            'is_system_generated' => true,
        ]);

        CostItem::create([
            'name' => 'A9',
            'description' => 'Exceptions: Staff',
            'active' => true,
            'is_system_generated' => true,
        ]);

        CostItem::create([
            'name' => 'A10',
            'description' => 'Exceptions: Travel & Subsistence',
            'active' => true,
            'is_system_generated' => true,
        ]);

        CostItem::create([
            'name' => 'A11',
            'description' => 'Exceptions: Other',
            'active' => true,
            'is_system_generated' => true,
        ]);

        CostItem::create([
            'name' => 'A12',
            'description' => 'Exceptions: Student Internships',
            'active' => true,
            'is_system_generated' => true,
        ]);

        CostItem::create([
            'name' => 'A13',
            'description' => 'Exceptions:',
            'active' => true,
            'is_system_generated' => true,
        ]);
    }
}
