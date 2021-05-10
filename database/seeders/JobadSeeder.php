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
//        Skill::factory()->count(100)->create();

        Jobad::factory()->count(100)->create()->each(function ($jobad) {
            $jobad->skills()->attach(Skill::inRandomOrder()->get()->pluck('id')->take(rand(1, 4)));
        });

        $jobad = Jobad::factory()->create([
            'title' => 'Technical Marketer',
            'category_id' => '32'
        ]);
        $jobad->skills()->attach([69, 76]);


        $jobad = Jobad::factory()->create([
            'title' => 'Technical Support Representative',
            'category_id' => '11'
        ]);
        $jobad->skills()->attach([271]);



        $jobad = Jobad::factory()->create([
            'title' => 'Product Manager',
            'category_id' => '660'
        ]);
        $jobad->skills()->attach([266]);


        $jobad = Jobad::factory()->create([
            'title' => 'Web Developer',
            'category_id' => '50'
        ]);
        $jobad->skills()->attach([50]);


        $jobad = Jobad::factory()->create([
            'title' => 'Communications Consulting Specialist',
            'category_id' => 12
        ]);
        $jobad->skills()->attach([124]);

        $jobad = Jobad::factory()->create([
            'title' => 'Senior React Engineer',
            'category_id' => 17
        ]);
        $jobad->skills()->attach([46]);


        $jobad = Jobad::factory()->create([
            'title' => 'TV and Movies Editor',
            'category_id' => 18
        ]);
        $jobad->skills()->attach([255]);

        $jobad = Jobad::factory()->create([
            'title' => 'Spanish to English Translation, Transcription - Insurance Audios',
            'category_id' => 48
        ]);
        $jobad->skills()->attach([254]);


    }
}
