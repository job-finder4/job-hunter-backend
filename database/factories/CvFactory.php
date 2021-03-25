<?php

namespace Database\Factories;

use App\Models\Cv;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CvFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cv::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $file = UploadedFile::fake()->create($this->faker->jobTitle,100,'application/pdf');
        Storage::disk('local')->put('cvs/',$file);

        return [
            'path' => 'cvs/'.$file->hashName(),
            'title' => $file->name,
            'user_id' => User::factory(),
        ];
    }
}
