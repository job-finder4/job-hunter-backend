<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Cv;
use App\Models\Jobad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cv_id' => Cv::factory(),
            'jobad_id' => Jobad::factory(),
        ];
    }
}
