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
            'active' => true,
        ]);
        
        CostItem::create([
            'name' => 'A2',
            'description' => 'Consumables',
            'active' => true,
        ]);
        
        CostItem::create([
            'name' => 'A3',
            'description' => 'Travel & Subsistence',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A4',
            'description' => 'Directly incurred: Other cost ',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A5',
            'description' => 'Directly incurred: Exceptions Other',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A6',
            'description' => 'Directly allocated: Investigators',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A7',
            'description' => 'Directly allocated: Estates',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A8',
            'description' => 'Directly allocated: Other cost',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A9',
            'description' => 'Indirect costs',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A10',
            'description' => '  Exceptions: Staff',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A11',
            'description' => 'Exceptions: Travel & Subsistence',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A12',
            'description' => 'Exceptions: Student Internships',
            'active' => true,
        ]);

        CostItem::create([
            'name' => 'A13',
            'description' => 'Exceptions: Other cost',
            'active' => true,
        ]);
    }
}
