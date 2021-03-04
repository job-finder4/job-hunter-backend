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
            'job_type' => Jobad::REMOTE,
            'job_time' => Jobad::PART_TIME,
            'salary' => ['min_salary' => 1000, 'max_salary' => 1500],
            'expiration_date' => now()->addMonth(),
        ];
    }

    /**
     * @test
     */
    public function company_can_post_new_job_ad()
    {
        $this->actingAs($user = User::factory()->create(), 'api');

        $response = $this->post('/api/jobs', $this->getJobDetails())
            ->assertStatus(201);

        $job = Jobad::unapproved()->first();

        $this->assertNotNull($job);

        $response->assertJson([
            'id' => $job->id,
            'type' => 'jobads',
            'attributes' => [
                'title' => 'ceo',
                'location' => 'london',
                'company_id' => $user->id,
                'description' => 'this job require experience in ceo in big company',
                'min_salary' => 1000,
                'max_salary' => 1500,
                'job_time' => Jobad::PART_TIME,
                'job_type' => Jobad::REMOTE,
                'expiration_date' => now()->addMonth()->diffForHumans(),
                'approved_at' => null
            ],
        ]);
    }

    /**
     * @test
     */
    public function user_can_retrieve_all_jobs_ad()
    {
        $this->withoutExceptionHandling();
        Jobad::factory()->count(3)->create();
        $this->actingAs(User::factory()->create(), 'api');
        $this->get('/api/jobs')
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
        $this->actingAs($user = User::factory()->create(), 'api');
        Jobad::factory()->unapproved()->count(2)->create();
        $approved_job = Jobad::factory()->create();
        $this->get('/api/jobs')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'id' => $approved_job->id,
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
        $this->get('/api/jobs')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'id' => $unExpiredJob->id,
                    ]
                ]
            ]);
    }

}
