<?php

namespace Tests\Feature;

use App\Models\Jobad;
use App\Models\Skill;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateJobTest extends TestCase
{
    /**
     * @group dani
     */

    use RefreshDatabase;

    public function getJobDetails()
    {
        $this->seed(SkillSeeder::class);

        return [
            'title' => 'ceo',
//            'min_salary' => 1000,
//            'max_salary' => 1500,
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
    public function a_company_can_update_job_ad()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');

        $jobad=Jobad::factory()->create();
//        dd($jobad);

        $response = $this->put('/api/jobads/'.$jobad->id, $this->getJobDetails())
            ->assertStatus(200);

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
}
