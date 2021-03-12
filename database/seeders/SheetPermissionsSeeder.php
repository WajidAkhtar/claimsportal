<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\System\Models\SheetPermission;

class SheetPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SheetPermission::create([
            'permission' => 'READ_WRITE_ALL'
        ]);
        SheetPermission::create([
            'permission' => 'WRITE_ONLY_FORECAST'
        ]);
        SheetPermission::create([
            'permission' => 'READ_ONLY'
        ]);
        SheetPermission::create([
            'permission' => 'LEAD_USER'
        ]);
    }
}
