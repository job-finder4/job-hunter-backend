<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // admin user
        $user = User::factory()->create([
            'name'  => 'Admin',
            'email' => 'admin@example.com',
            'password'=>bcrypt('admin')
        ]);


        $user->assignRole('admin');

//        // random dummy users
//        User::factory(9)->create();
    }
}
