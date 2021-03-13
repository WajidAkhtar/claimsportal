<?php

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use App\Domains\Auth\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;

/**
 * Class UserTableSeeder.
 */
class UserSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Add the master administrator, user id of 1
        User::create([
            'type' => User::TYPE_ADMIN,
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'job_title' => 'Administration',
            'department' => 'Administration',
            'email' => 'admin@admin.com',
            'password' => 'secret',
            'email_verified_at' => now(),
            'active' => true,
        ]);

        if (app()->environment(['local', 'testing'])) {
            // User::create([
            //     'type' => User::TYPE_USER,
            //     'name' => 'Test User',
            //     'email' => 'user@user.com',
            //     'password' => 'secret',
            //     'email_verified_at' => now(),
            //     'active' => true,
            // ]);
        }

        $this->enableForeignKeys();
    }
}
