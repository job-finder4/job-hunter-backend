<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
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

        return [
            'name' => $this->faker->randomElement($categories),
        ];
    }
}
