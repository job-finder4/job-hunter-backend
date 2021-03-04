<?php

namespace Tests\Feature;

use App\Models\Jobad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function getProfileDetails()
    {
        return [
            'phone_number' => '0936689359',
            'location' => [
                'city' => 'los angeles',
                'country' => 'usa'
            ],
            'education' => [
                [
                    'graduation_year' => 2021,
                    'degree' => 'bachelors',
                    'institution' => 'tishreen university',
                    'study_field' => 'very good'
                ]
            ],
            'workExperience' => [
                [
                    'job_title' => 'seo',
                    'company_name' => 'google',
                    'start_date' => '2/2/2010',
                    'end_date' => '2/2/2020',
                    'industry' => 'IT',
                    'job_category' => 'software developer',
                    'job_subcategory' => 'web development',
                    'job_description' => 'it is very easy job to me its very handful',
                ]
            ],
            'languages' => [
                'english', 'arabic'
            ],
            'visibility' => true
        ];
    }

    /**
     * @test
    */
    public function user_can_add_profile_information()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create(), 'api');
        $response = $this->post('/api/user/'.$user->id,$this->getProfileDetails())
        ->assertStatus(201);
        $this->assertNotNull($user->profile, 'user profile not found');
    }

}
