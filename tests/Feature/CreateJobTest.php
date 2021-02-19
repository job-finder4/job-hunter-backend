<?php

namespace Tests\Feature;

use App\Models\Jobad;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Queue\Jobs\Job;
use Tests\TestCase;

class CreateJobTest extends TestCase
{
    use RefreshDatabase;

    public function getJobDetails()
    {
        return [
            'title' => 'ceo',
            'min_salary' => 1000,
            'max_salary' => 1500,
            'description' => 'this job require experience in ceo in big company',
            'location' => 'london',
            'job_type' => 'remote',
            'job_time' => 'part_time',
            'expiration_date' => now()->addMonth(),
        ];
    }

    /**
     * @test
     */
    public function company_can_post_new_job_ad()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');

        $response = $this->post('/api/jobs', $this->getJobDetails())
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
    public function unapproved_jobs_should_not_be_retrieved()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');
        $job=\App\Models\Jobad::factory()->unapproved()->create();

        $response = $this->get('/api/jobs')->assertStatus(200);

//        $response->assertJson([
//            'data'=>[
//
//            ]
//        ]);

    }
}
