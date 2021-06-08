<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Model;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\User;
use App\Profile\Education;
use App\Profile\UserProfile;
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
        $eduLength = $this->faker->numberBetween(1, 5);
        $workLength = $this->faker->numberBetween(1, 5);
        $works = [];
        $educations = [];

        for ($i = 0; $i < $eduLength; $i++) {
            $educations[] = [
                'degree' => $this->faker->randomElement(['Mr.', 'Dr.', 'Bachelors']),
                'graduation_year' => $this->faker->year(2020),
                'institution' => $this->faker->randomElement(['Mit', 'Harvard']),
                'study_field' => $this->faker->domainWord()
            ];
        }

        for ($i = 0; $i < $workLength; $i++) {
            $works[] = [
                'job_title' => $this->faker->jobTitle,
                'company_name' => $this->faker->company,
                'start_date' => $start_date = $this->faker->dateTimeBetween('-10 years', 'now'),
                'end_date' => $this->faker->dateTimeBetween($start_date, 'now'),
                'industry' => $this->faker->domainName,
                'job_category' =>  $this->faker->domainName,
                'job_subcategory' =>  $this->faker->domainName,
                'job_description' => $this->faker->realText()
            ];
        }

        return [
            'user_id' => User::factory(),
            'details' => UserProfile::make([
                'phone_number' => $this->faker->phoneNumber,
                'location' => [
                    'country' => $this->faker->country,
                    'city' => $this->faker->city
                ],
                'educations' => $educations,
                'works_experience' => $works,
                'languages' => [
                    'english', 'arabic'
                ]
            ]),
            'visible' => $this->faker->boolean,
        ];

    }
}
