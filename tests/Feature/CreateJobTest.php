<?php

namespace Tests\Feature;

use App\Exceptions\MyModelNotFoundException;
use App\Models\Jobad;
use App\Models\Skill;
use App\Models\User;
use Database\Factories\UserFactory;
use Database\Seeders\SkillSeeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Arr;
use Tests\TestCase;

class CreateJobTest extends TestCase
{
    use RefreshDatabase;

    public function getJobDetails()
    {
        $this->seed(SkillSeeder::class);

        return [
            'title' => 'ceo',
            'min_salary' => 1000,
            'max_salary' => 1500,
            'description' => 'this job require experience in ceo in big company',
            'location' => 'london',
            'job_type' => Jobad::REMOTE,
            'job_time' => Jobad::PART_TIME,
            'salary' => ['min_salary' => 1000, 'max_salary' => 1500],
            'expiration_date' => now()->addMonth(),
            'skills' => Skill::take(2)->get()->pluck('id'),
            'approved_at' => now()
        ];
    }

    /**
     * @test
     */
    public function company_can_post_new_job_ad()
    {
        $this->withoutExceptionHandling();

        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authorize::class);

        $this->actingAs($user = User::factory()->create(), 'api');

        $response = $this->post('/api/jobads', $this->getJobDetails())
            ->assertStatus(201);

        $job = Jobad::unapproved()->first();

        $this->assertNotNull($job);

        $response->assertJson([
            'data' => [
                'id' => $job->id,
                'type' => 'jobads',
                'attributes' => [
                    'title' => 'ceo',
                    'location' => 'london',
                    'company' => [
                        'data' => [
                            'id' => $user->id,
                            'attributes' => [
                                'name' => $user->name
                            ]
                        ]
                    ],

                    'description' => 'this job require experience in ceo in big company',
                    'min_salary' => 1000,
                    'max_salary' => 1500,
                    'job_time' => Jobad::PART_TIME,
                    'job_type' => Jobad::REMOTE,
                    'expiration_date' => now()->addMonth()->diffForHumans(),
                    'approved_at' => null
                ],
            ]
        ]);
    }

    /**
     * @test
     */
    public function a_job_ad_cannot_be_created_without_provide_skills()
    {

        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authorize::class);


//        $this->withoutExceptionHandling();
        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');

        $response = $this->post('/api/jobads', array_merge($this->getJobDetails(), ['skills' => '']))
            ->assertStatus(422);

        $responseString = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('skills', $responseString['errors']['meta']);
    }

    /**
     * @test
     */
    public function job_ads_are_returned_with_required_skills()
    {
        $this->withoutExceptionHandling();

        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authorize::class);

        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');

        $approved_job = Jobad::factory()->create();

        $this->seed(SkillSeeder::class);

        $skill1 = skill::where('id', 1)->first();
        $skill2 = skill::where('id', 2)->first();

        $approved_job->skills()->sync([$skill1->id, $skill2->id]);

        $response = $this->get('/api/jobads')->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'id' => $approved_job->id,
                        'type' => 'jobads',
                        'attributes' => [
                            'skills' => [
                                'data' =>
                                    [
                                        [
                                            'data' => [
                                                'type' => 'skills',
                                                'id' => $skill1->id,
                                                'attributes' => [
                                                    'name' => $skill1->name,
                                                    'parent_id' => $skill1->parent_id
                                                ]
                                            ]
                                        ],
                                        [
                                            'data' => [
                                                'type' => 'skills',
                                                'id' => $skill2->id,
                                                'attributes' => [
                                                    'name' => $skill2->name,
                                                    'parent_id' => $skill2->parent_id
                                                ]
                                            ]
                                        ]
                                    ]
                            ]
                        ],
                    ]
                ]
            ]

        ]);
    }


    /**
     * @test
     */
    public function a_company_can_add_skills_to_the_job_ad_only_from_available_skills()
    {

        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authorize::class);
        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');

        $this->seed(SkillSeeder::class);
        $skills = Skill::get()->toTree();

        $unavialableSkill = ['id' => 23237777999];

        $response = $this->post('/api/jobads',
            array_merge($this->getJobDetails(), ['skills' => [$unavialableSkill]]))
            ->assertStatus(404);

        $jobad = Jobad::first();
        $this->assertNull($jobad);

        $response->assertJson([
            'errors' => [
                'code' => 404,
                'description' => 'No query results for model [App\\Models\\Skill] ' . $unavialableSkill['id']
            ]
        ]);
    }

    /**
     * @test
     */
    public function user_can_retrieve_all_jobs_ad()
    {
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authorize::class);
        $this->withoutExceptionHandling();

        Jobad::factory()->count(3)->create();
        $this->actingAs(User::factory()->create(), 'api');
        $this->get('/api/jobads')
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'link' => [
                    'self' => url('/api/jobs')
                ]
            ]);
    }

    /**
     * @test
     */
    public function unapproved_jobs_should_not_be_retrieved()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = User::factory()->create(), 'api');
        Jobad::factory()->unapproved()->count(2)->create();
        $approved_job = Jobad::factory()->create();

        $this->get('/api/jobads')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'data' => [
                            'id' => $approved_job->id
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function expired_jobs_should_not_be_retrieved()
    {
        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');
        Jobad::factory()->expired()->count(2)->create();
        $unExpiredJob = Jobad::factory()->create();
        $this->get('/api/jobads')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'data' =>
                            [
                                'id' => $unExpiredJob->id,
                            ]
                    ]
                ]
            ]);
    }


    //-------------------daniel tests ---------------------------------------

    /**
     * @test
     */
    public function jobads_should_be_returned_with_pagination()
    {
        $this->withoutExceptionHandling();


        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');
        Jobad::factory()->count(2)->create();

        $resp = $this->get('/api/jobads')->assertStatus(200);


        $resp->assertJson([
            'data' => [
                [
                    'data' =>
                        []
                ]
            ]
        ]);
    }

}
