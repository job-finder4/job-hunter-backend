<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
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
        foreach ($categories as $category) {
            Category::create(['name'=>$category]);
        }
    }
}
