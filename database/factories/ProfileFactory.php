<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\User;
use App\Profile\Education;
use App\Profile\WorkExperience;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'details' => [
                'phone_number' => $this->faker->phoneNumber,
                'location' => ['country' => $this->faker->country, 'city' => $this->faker->city],
                'educations' => [
                    [
                        'degree' => $this->faker->title,
                        'graduation_year' => $this->faker->year,
                        'institution' => $this->faker->title,
                        'study_field' => $this->faker->title
                    ]
                ],
                'works_experience' => [
                    [
                        'job_title' => $this->faker->jobTitle,
                        'company_name' => $this->faker->company,
                        'start_date' => $start_date = $this->faker->dateTimeBetween('-10 years', 'now'),
                        'end_date' => $this->faker->dateTimeBetween($start_date, 'now'),
                        'industry' => $this->faker->word,
                        'job_category' => $this->faker->jobTitle,
                        'job_subcategory' => $this->faker->text,
                        'job_description' => $this->faker->paragraph
                    ]
                ],
                'languages' => [
                    'english', 'arabic'
                ]
            ],
            'visible' => true,
        ];

    }
}
