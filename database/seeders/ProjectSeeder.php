<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\Traits\TruncateTable;
use Database\Seeders\Project\CostItemSeeder;
use Database\Seeders\Traits\DisableForeignKeys;

/**
 * Class ProjectSeeder.
 */
class ProjectSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Reset cached roles and permissions
        Artisan::call('cache:clear');
        $this->truncateMultiple([
            'cost_items',
        ]);

        $this->call(CostItemSeeder::class);
        
        $this->enableForeignKeys();
    }
}
