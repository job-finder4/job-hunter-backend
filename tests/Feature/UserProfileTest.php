<?php

namespace Tests\Feature;

use App\Http\Resources\SkillCollection;
use App\Http\Resources\User as UserResource;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\User;
use App\Profile\UserProfile;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function getProfileDetails()
    {
        $this->seed(SkillSeeder::class);
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
            'visibility' => true,
            'skills' => Skill::get()->pluck('id')->take(2)->toArray(),
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
        $this->actingAs($user = User::factory()->create(), 'api');

        $response = $this->post('/api/users/' . $user->id . '/profile', $this->getProfileDetails())
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
                    ]
                ],
                'links' => [
                    'self' => '/api/users/' . $user->id . '/profile'
                ]
            ]
        );
    }

    /**
     * @test
     */
    public function skills_field_are_required_to_create_profile()
    {
        $this->actingAs($profileOwner = User::factory()->create(), 'api');
        $resp =$this->post('/api/users/' . $profileOwner->id . '/profile', Arr::except($this->getProfileDetails(),'skills'))
            ->assertStatus(422);
        $this->assertObjectHasAttribute('skills',json_decode($resp->getContent())->errors->meta);
    }

    /**
     * @test
     */
    public function user_can_retrieve_profile_belongs_to_another_user()
    {

        $this->actingAs($profileOwner = User::factory()->create(), 'api');
        $this->post('/api/users/' . $profileOwner->id . '/profile', $this->getProfileDetails())
            ->assertStatus(201);
        $profile = $profileOwner->profile;

        $this->actingAs($user = User::factory()->create(), 'api');
        $response = $this->get('/api/users/' . $profileOwner->id . '/profile')->assertStatus(200);

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
                        'user' => ['type' => 'users',
                            'id' => $profileOwner->id,
                            'attributes' => [
                                'email' => $profileOwner->email,
                                'name' => $profileOwner->name
                            ],
                        ],
                    ]
                ],
                'links' => [
                    'self' => '/api/users/' . $profileOwner->id . '/profile'
                ]
            ]);
    }

    /**
     * @test
     */
    public function user_can_assign_skills_to_his_profile()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create(), 'api');
        $skillIds = $this->getProfileDetails()['skills'];
        $skills = Skill::find($skillIds);

        $response = $this->post('/api/users/' . $user->id . '/profile', $this->getProfileDetails())
            ->assertStatus(201);

        $this->assertNotNull($user->skills, 'there is no skills assigned to the user');
        $this->assertEquals(2, $user->skills()->count());
        $response->assertJsonCount(2, 'data.attributes.skills.data');
        $response->assertJson([
            'data' => [
                'attributes' => [
                    "skills" => [
                        "data" => [
                            [
                                "data" => [
                                    "type" => "skills",
                                    "id" => $skills[0]->id,
                                    "attributes" => [
                                        "name" => $skills[0]->name,
                                        "parent_id" => $skills[0]->parent_id
                                    ]
                                ]
                            ],
                            [
                                "data" =>
                                    [
                                        "type" => "skills",
                                        "id" => $skills[1]->id,
                                        "attributes" =>
                                            [
                                                "name" => $skills[1]->name,
                                                "parent_id" => $skills[1]->parent_id
                                            ]
                                    ]
                            ]
                        ]
                    ]]]]);
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

        $response = $this->post('/api/users/' . $user->id . '/profile', $this->getAdditionalAttributes())
            ->assertStatus(201);

        $profile = $user->profile;
        $this->assertNotNull($profile);
        $this->assertCount(2, $profile->details->educations);
        $this->assertCount(1, Profile::get(), 'there are more one profile for a single user');;
    }

    /**
     * @test
     */
    public function user_can_update_his_profile()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create(), 'api');

        $resp = $this->post('api/users/' . $user->id . './profile', $this->getProfileDetails());

        $profile = json_decode($resp->getContent())->data->attributes;

        $education = $profile->details->educations[0];
        $education->degree = 'DR';

        $location = ['city' => 'new york', 'country' => 'usa'];
        $profile->details->location = $location['country'] . ', ' . $location['city'];

        $work_experience = $profile->details->works_experience[0];
        $work_experience->job_category = 'backend web developer';
        $profile->details->works_experience;

        $storedProfile = $user->profile;
        $storedProfile->details->location = 'usa, new york';
        $storedProfile->details->educations[0]->degree = 'DR';
        $storedProfile->details->works_experience[0]->job_category = 'backend web developer';

        $details = [
            'location' => $location,
            'educations' => [$education],
            'works_experience' => [$work_experience]
        ];

        $this->patch('/api/users/' . $user->id . '/profile/', [
            'details' => $details,
            'visible' => false
        ]);

        $newProfile = $user->profile;

        $this->assertEquals($storedProfile->details->location, $newProfile->details->location);
        $this->assertObjectEquals($storedProfile->details->educations[0], $newProfile->details->educations[0], 'equals');
        $this->assertObjectEquals($storedProfile->details->works_experience[0], $newProfile->details->works_experience[0], 'equals');
        $this->assertObjectEquals($storedProfile->details, $newProfile->details, 'equals');
        $this->assertEquals(false, $newProfile->visible);
    }

}
