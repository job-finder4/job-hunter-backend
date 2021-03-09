<?php

namespace Database\Factories;

use App\Models\Jobad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Jobad::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */


    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'description' => $this->faker->text,
            'user_id' => User::factory(),
            'location' => $this->faker->address,
            'salary' => ['min_salary'=>$tmp=$this->faker->numberBetween(100,5000),'max_salary'=>$this->faker->numberBetween($tmp,$tmp+5000)],
            'expiration_date'=>$this->faker->dateTimeBetween(now()->addWeek(),now()->addWeeks(8)),
            'job_type' => $this->faker->randomElement([Jobad::FULL_TIME,Jobad::PART_TIME]),
            'job_time' => $this->faker->randomElement([Jobad::REMOTE,Jobad::ON_SITE]),
            'approved_at' => now(),
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unapproved()
    {
        return $this->state(function (array $attributes) {
            return [
                'approved_at' => null,
            ];
        });
    }

    public function expired()
    {

        return $this->state(function (array $attributes){
           return [
               'expiration_date' => now()->subMonth()
           ];
        });
    }

}
