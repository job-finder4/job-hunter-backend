<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(PermissionSeeder::class);
//        $this->call(SkillSeeder::class);
//        $this->call(CategorySeeder::class);
        $this->call(JobadSeeder::class);
//        $this->call(UsersTableSeeder::class);
        // \App\Models\User::factory(10)->create();
    }
}
