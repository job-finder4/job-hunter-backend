<?php

namespace Tests\Feature;

use App\Http\Resources\Jobad as JobadResource;
use App\Http\Resources\User as UserResource;
use App\Models\Application;
use App\Models\Jobad;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JobApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function applicationData()
    {

        return [

        ];
    }
    /**
     * @test
     */
    public function user_can_apply_a_job()
    {
        $this->withoutExceptionHandling();
        $jobad = Jobad::factory()->create();

        $this->actingAs($user = User::factory()->create());

        $resp = $this->post('/api/jobads/' . $jobad->id . '/applications', [])
            ->assertStatus(201);

        $applications = Application::all();
        $userApplications = $user->applications()->first();

        $this->assertCount(1, $applications);
        $this->assertNotNull($userApplications);
        $this->assertEquals($jobad->id, $userApplications->jobad_id);
        $this->assertEquals($user->id, $userApplications->user_id);
        $resp->assertJson([
            'data' => [
                'type' => 'application',
                'id' => $userApplications->id,
                'attributes' => [
                    'applied_at' => now()->toFormattedDateString(),
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function user_and_jobad_resource_should_retrieved_with_application_resource()
    {
        $this->withoutExceptionHandling();
        $application = Application::factory()->create();

        $this->actingAs($user = User::factory()->create(), 'api');

        $this->get('api/jobads/' . $application->jobad_id . '/applications/' . $application->id)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'type' => 'application',
                    'id' => $application->id,
                    'attributes' => [
                        'user' => [
                            'data' => [
                                'type' => 'user',
                                'id' => $application->user->id,
                                'attributes' => []
                            ]
                        ],
                        'jobad' => [
                            'data' => [
                                'type' => 'jobads',
                                'id' => $application->jobad->id,
                                'attributes' => []
                            ]
                        ],
                        'applied_at' => now()->toFormattedDateString(),
                    ]
                ]
            ]);
    }

    /**
     * @test
    */
    public function user_can_apply_only_on_active_jobad()
    {
        $this->actingAs($user = User::factory()->create());
        $unApprovedJobad = Jobad::factory()->unapproved()->create();
        $expiredJobad = Jobad::factory()->expired()->create();

        $this->post('/api/jobads/' . $unApprovedJobad->id . '/applications', [])
            ->assertStatus(404);
        $this->post('/api/jobads/' . $expiredJobad->id . '/applications', [])
            ->assertStatus(404);
    }

    /**
     * @test
    */
    public function user_should_assign_cv_to_his_application_request()
    {

    }

}
