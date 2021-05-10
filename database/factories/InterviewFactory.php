<?php

namespace Database\Factories;

use App\Models\Interview;
use App\Models\Jobad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Interview::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $day = now()->addDays($this->faker->numberBetween(1,10));
        $hour = $this->faker->numberBetween(0,23);

        return [
            'user_id' => User::factory(),
            'jobad_id' => Jobad::factory(),
            'start_date' => $day->setTime($hour,00)->toDateTimeString(),
            'end_date' => $day->setTime($hour,30)->toDateTimeString(),
            'contact_info' => $this->faker->companyEmail
        ];
    }

    public function unreserved()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => null,
            ];
        });
    }
}
