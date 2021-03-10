<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\System\Models\Pool;

class PoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pool::create([
            'code' => 'EPS',
            'name' => 'Engineering & Physical Sciences'
        ]);

        Pool::create([
            'code' => 'LES',
            'name' => 'Life & Environmental Sciences'
        ]);

        Pool::create([
            'code' => 'CoSS',
            'name' => 'College of Social Sciences'
        ]);

        Pool::create([
            'code' => 'CAL',
            'name' => 'College of Arts & Law'
        ]);

        Pool::create([
            'code' => 'MDS',
            'name' => 'College of Medical & Dental Sciences'
        ]);

    }
}
