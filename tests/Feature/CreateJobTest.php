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
            'job_type' => 'remote',
            'job_time' => 'part_time',
            'expiration_date' => now()->addMonth(),
            'skills' => Skill::take(2)->get(),
            'approved_at' => now()
        ];
    }

    /**
     * @test
     */
    public function company_can_post_new_job_ad()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');


        $response = $this->post('/api/jobads', $this->getJobDetails())
            ->assertStatus(201);


        $job = \App\Models\Jobad::first();
        $this->assertNotNull($job);

        $response->assertJson([
            'data' => [
                'id' => $job->id,
                'type' => 'jobads',
                'attributes' => [
                    'title' => 'ceo',
                    'location' => 'london',
                    'company_id' => $user->id,
                    'description' => 'this job require experience in ceo in big company',
                    'salary' => ['min_salary' => 1000, 'max_salary' => 1500],
                    'job_time' => 'part_time',
                    'job_type' => 'remote',
                    'expiration_date' => now()->addMonth()->diffForHumans(),
                ],
            ]
        ]);
    }

    /**
     * @test
     */
    public function a_job_ad_cannot_be_created_without_provide_skills()
    {
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
//        $this->withoutExceptionHandling();
        $skill1 = $this->getJobDetails()['skills'][0];
        $skill2 = $this->getJobDetails()['skills'][1];

        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');
        $response = $this->post('/api/jobads',
            array_merge($this->getJobDetails(), ['skills' => [$skill1, $skill2]]))
            ->assertStatus(201);

        $job = \App\Models\Jobad::first();
//        dd($response->getContent());
        $response->assertJson([
            'data' => [
                'id' => $job->id,
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
        ]);
    }


    /**
     * @test
     */
    public function a_company_can_add_skills_to_the_job_ad_only_from_available_skills()
    {
        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');

        $this->seed(SkillSeeder::class);
        $skills = Skill::get()->toTree();

        $unavialableSkill = ['id' => 23237777999, 'name' => 'daniel'];

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

}
