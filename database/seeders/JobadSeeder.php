<?php

namespace Database\Seeders;

use App\Models\Category;
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
        $categories = [
            "Account Management",
            "Accounting & Finance",
            "Administrative",
            "Advertising & PR",
            "Animals & Wildlife",
            "Art & Creative",
            "Bilingual",
            "Business Development",
            "Call Center",
            "Communications",
            "Computer & IT",
            "Consulting",
            "Customer Service",
            "Data Entry",
            "Editing",
            "Education & Training",
            "Engineering",
            "Entertainment & Media ",
            //
            "Environmental & Green",
            "Event Planning",
            "Fashion & Beauty",
            "Food & Beverage",
            "Government & Politics",
            "Graphic Design",
            "HR & Recruiting",
            "Human Services",
            "Insurance",
            "International",
            "Internet & Ecommerce",
            "Legal",
            "Manufacturing",
            "Marketing",
            "Math & Economics",
            "Medical & Health",
            "Mortgage & Real Estate",
            "News & Journalism ",
            //
            "Nonprofit & Philanthropy",
            "Operations",
            "Project Management",
            "Research",
            "Retail",
            "Sales",
            "Science",
            "Software Development",
            "Sports & Fitness",
            "Telemarketing",
            "Transcription",
            "Translation",
            "Travel & Hospitality",
            "Web Design",
            "Writing",
            "Youth & Children"
        ];

//        Skill::factory()->count(30)->create();

//        foreach ($categories as $category){
//            Category::factory()->create(['name'=>$category]);
//        }
//        Jobad::factory()->count(30)->create()->each(function ($jobad){
//            $jobad->skills()->attach(Skill::inRandomOrder()->get()->pluck('id')->take(rand(1,3)));
//        });

//        Jobad::factory()->create([
//            'description'=>'',
//            'title'=>'',
//        ]);

        Jobad::factory()->count(100)->create()->each(function ($jobad) {
            $jobad->skills()->attach(Skill::inRandomOrder()->get()->pluck('id')->take(rand(1, 4)));
        });

        $jobad=Jobad::factory()->create([
            'title' => 'Technical Marketer',
            'category_id' => '32'
        ]);
        $jobad->skills()->attach([69, 76]);


        $jobad=Jobad::factory()->create([
            'title' => 'Technical Support Representative',
            'category_id' => '11'
        ]);
        $jobad->skills()->attach([271]);



        $jobad=Jobad::factory()->create([
            'title' => 'Product Manager',
            'category_id' => '660'
        ]);
        $jobad->skills()->attach([266]);


        $jobad=Jobad::factory()->create([
            'title' => 'Web Developer',
            'category_id' => '50'
        ]);
        $jobad->skills()->attach([50]);


        $jobad=Jobad::factory()->create([
            'title' => 'Communications Consulting Specialist',
            'category_id' => 12
        ]);
        $jobad->skills()->attach([124]);

        $jobad=Jobad::factory()->create([
            'title' => 'Senior React Engineer',
            'category_id' => 17
        ]);
        $jobad->skills()->attach([46]);


        $jobad=Jobad::factory()->create([
            'title' => 'TV and Movies Editor',
            'category_id' => 18
        ]);
        $jobad->skills()->attach([255]);

        $jobad=Jobad::factory()->create([
            'title' => 'Spanish to English Translation, Transcription - Insurance Audios',
            'category_id' => 48
        ]);
        $jobad->skills()->attach([254]);
    }
}
