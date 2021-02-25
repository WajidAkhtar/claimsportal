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

        CostItem::create([
            'name' => 'A4',
            'description' => 'Directly incurred: Other cost ',
            'value' => '0.00',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A4a',
            'description' => 'Directly incurred: Exceptions Other',
            'value' => '0.00',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A5',
            'description' => 'Directly allocated: Investigators',
            'value' => '0.00',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A6',
            'description' => 'Directly allocated: Estates',
            'value' => '0.00',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A7',
            'description' => 'Directly allocated: Other cost',
            'value' => '0.00',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A8',
            'description' => 'Indirect costs',
            'value' => '0.00',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A9',
            'description' => '  Exceptions: Staff',
            'value' => '0.00',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A10',
            'description' => 'Exceptions: Travel & Subsistence',
            'value' => '0.00',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A11',
            'description' => 'Exceptions: Student Internships',
            'value' => '0.00',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A12',
            'description' => 'Exceptions: Other cost',
            'value' => '0.00',
            'active' => true,
        ]);
    }
}
