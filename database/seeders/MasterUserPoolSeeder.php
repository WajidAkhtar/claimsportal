<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\System\Models\Pool;
use App\Domains\Auth\Models\User;

class MasterUserPoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::find(1)->pools()->sync(Pool::all()->pluck('id')->toArray());
        User::find(2)->pools()->sync(Pool::all()->pluck('id')->toArray());
    }
}
