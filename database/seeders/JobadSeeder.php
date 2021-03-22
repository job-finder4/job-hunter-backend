<?php

namespace Database\Seeders;

use App\Models\Jobad;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Skill::factory()->count(30)->create();

        Jobad::factory()->count(30)->create()->each(function ($jobad){
            $jobad->skills()->attach(Skill::inRandomOrder()->get()->pluck('id')->take(rand(1,3)));
        });
    }
}
