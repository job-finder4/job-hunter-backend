<?php

namespace Tests\Feature;

use App\Models\Jobad;
use App\Models\Profile;
use App\Models\User;
use App\Profile\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use phpDocumentor\Reflection\Location;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function getProfileDetails()
    {
        return [
            'details' => [
                'phone_number' => '0936689359',
                'location' => [
                    'city' => 'los angeles',
                    'country' => 'usa'
                ],
                'educations' => [
                    [
                        'graduation_year' => 2021,
                        'degree' => 'bachelors',
                        'institution' => 'tishreen university',
                        'study_field' => 'very good'
                    ]
                ],
                'works_experience' => [
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
            ],
            'visibility' => true
        ];
    }

    public function getAdditionalAttributes()
    {
        return [
            'details' => [
                'educations' => [
                    'graduation_year' => 2015,
                    'degree' => 'master',
                    'institution' => 'tishreen university',
                    'study_field' => 'very good'
                ]
            ],
        ];
    }

    /**
     * @test
     */
    public function user_can_add_profile_information()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create(), 'api');

        $response = $this->post('/api/user/' . $user->id.'/profile', $this->getProfileDetails())
            ->assertStatus(201);

        $profile = $user->profile;

        $this->assertNotNull($user->profile, 'user profile not found');
        $this->assertInstanceOf(UserProfile::class, $profile->details);
        $response->assertJson(
            [
                'data' => [
                    'type' => 'profile',
                    'id' => $profile->id,
                    'attributes' => [
                        'details' => [
                            'phone_number' => '0936689359',
                            'location' => 'los angeles, usa',
                            'educations' => [
                                [
                                    'graduation_year' => 2021,
                                    'degree' => 'bachelors',
                                    'institution' => 'tishreen university',
                                    'study_field' => 'very good'
                                ]
                            ],
                            'works_experience' => [
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
                        ],
                        'user_id' => $user->id
                    ]
                ],
                'links' => [
                    'self' => '/api/user/' . $user->id . '/profile'
                ]
            ]
        );
    }

    /**
     * @test
     */
    public function user_can_add_additional_details_to_his_profile()
    {
        $this->actingAs($user = User::factory()->create(), 'api');

        $profile = $user->profile()->create([
            'details' => UserProfile::make($this->getProfileDetails()['details'])
        ]);

        $response = $this->post('/api/user/' . $user->id.'/profile', $this->getAdditionalAttributes())
            ->assertStatus(201);

        $profile = $user->profile;
        $this->assertNotNull($profile);
        $this->assertCount(2, $profile->details->educations);
        $this->assertCount(1, Profile::get(),'there are more one profile for a single user');;
    }

    /**
     * @test
    */
        public function user_can_update_his_profile()
    {
        $this->actingAs($user = User::factory()->create(), 'api');

        $resp = $this->post('api/user/'.$user->id.'./profile',$this->getProfileDetails());

        $profile = json_decode($resp->getContent())->data->attributes;

        $education = $profile->details->educations[0];
        $education->degree = 'DR';

        $location = ['city' => 'new york','country' => 'usa'];
        $profile->details->location = $location['country'].', '.$location['city'];

        $work_experience = $profile->details->works_experience[0];
        $work_experience->job_category = 'backend web developer';
        $profile->details->works_experience ;

        $storedProfile = $user->profile;
        $storedProfile->details->location = 'usa, new york';
        $storedProfile->details->educations[0]->degree = 'DR';
        $storedProfile->details->works_experience[0]->job_category = 'backend web developer';

        $details = [
            'location' => $location,
            'educations' => [$education],
            'works_experience' => [$work_experience]
        ];

        $this->patch('/api/user/'.$user->id.'/profile/'.$user->profile->id,[
            'details' => $details,
            'visible' => false
        ]);

        $newProfile = $user->profile;

        $this->assertEquals($storedProfile->details->location, $newProfile->details->location);
        $this->assertObjectEquals($storedProfile->details->educations[0], $newProfile->details->educations[0],'equals');
        $this->assertObjectEquals($storedProfile->details->works_experience[0], $newProfile->details->works_experience[0],'equals');
        $this->assertObjectEquals($storedProfile->details, $newProfile->details,'equals');
        $this->assertEquals(false,$newProfile->visible);
    }

}
