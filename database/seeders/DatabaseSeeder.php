<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\Skill;
use App\Models\User;
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
//        $this->call(JobadSeeder::class);
//        $this->call(UsersTableSeeder::class);

        User::factory()
            ->count(10)
            ->has(Profile::factory())
            ->create()->each(function ($user) {
                $user->skills()->attach(
                    Skill::inRandomOrder()
                        ->get()
                        ->take(rand(1, 5))
                        ->pluck('id')
                );
            });
    }
}
