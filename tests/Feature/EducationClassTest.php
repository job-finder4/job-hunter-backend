<?php

namespace Tests\Feature;

use App\Profile\Education;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EducationClassTest extends TestCase
{
    public function getEduDetails()
    {
        return [
                'graduation_year' => 2021,
                'degree' => 'bachelors',
                'institution' => 'tishreen university',
                'study_field' => 'very good'
        ];
    }

    /**
     * @test
     */
    public function test_create_many_method()
    {
        Education::createMany($this->getEduDetails());
    }
}
