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
<<<<<<< HEAD
//        Skill::factory()->count(100)->create();

=======
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

>>>>>>> origin/reports_feature
        Jobad::factory()->count(100)->create()->each(function ($jobad) {
            $jobad->skills()->attach(Skill::inRandomOrder()->get()->pluck('id')->take(rand(1, 4)));
        });

<<<<<<< HEAD
        $jobad = Jobad::factory()->create([
=======
        $jobad=Jobad::factory()->create([
>>>>>>> origin/reports_feature
            'title' => 'Technical Marketer',
            'category_id' => '32'
        ]);
        $jobad->skills()->attach([69, 76]);


<<<<<<< HEAD
        $jobad = Jobad::factory()->create([
=======
        $jobad=Jobad::factory()->create([
>>>>>>> origin/reports_feature
            'title' => 'Technical Support Representative',
            'category_id' => '11'
        ]);
        $jobad->skills()->attach([271]);



<<<<<<< HEAD
        $jobad = Jobad::factory()->create([
=======
        $jobad=Jobad::factory()->create([
>>>>>>> origin/reports_feature
            'title' => 'Product Manager',
            'category_id' => '660'
        ]);
        $jobad->skills()->attach([266]);


<<<<<<< HEAD
        $jobad = Jobad::factory()->create([
=======
        $jobad=Jobad::factory()->create([
>>>>>>> origin/reports_feature
            'title' => 'Web Developer',
            'category_id' => '50'
        ]);
        $jobad->skills()->attach([50]);


<<<<<<< HEAD
        $jobad = Jobad::factory()->create([
=======
        $jobad=Jobad::factory()->create([
>>>>>>> origin/reports_feature
            'title' => 'Communications Consulting Specialist',
            'category_id' => 12
        ]);
        $jobad->skills()->attach([124]);

<<<<<<< HEAD
        $jobad = Jobad::factory()->create([
=======
        $jobad=Jobad::factory()->create([
>>>>>>> origin/reports_feature
            'title' => 'Senior React Engineer',
            'category_id' => 17
        ]);
        $jobad->skills()->attach([46]);


<<<<<<< HEAD
        $jobad = Jobad::factory()->create([
=======
        $jobad=Jobad::factory()->create([
>>>>>>> origin/reports_feature
            'title' => 'TV and Movies Editor',
            'category_id' => 18
        ]);
        $jobad->skills()->attach([255]);

<<<<<<< HEAD
        $jobad = Jobad::factory()->create([
=======
        $jobad=Jobad::factory()->create([
>>>>>>> origin/reports_feature
            'title' => 'Spanish to English Translation, Transcription - Insurance Audios',
            'category_id' => 48
        ]);
        $jobad->skills()->attach([254]);
<<<<<<< HEAD


=======
>>>>>>> origin/reports_feature
    }
}
