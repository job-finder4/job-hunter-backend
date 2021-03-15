<?php

namespace Tests\Feature;

use App\Http\Resources\Jobad as JobadResource;
use App\Http\Resources\User as UserResource;
use App\Models\Application;
use App\Models\Cv;
use App\Models\Jobad;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobApplicationTest extends TestCase
{
    use RefreshDatabase;

    public $jobad;
    public $jobSeeker;
    public $company;
    public $cv;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->company = User::factory()->create();
        $this->jobad = Jobad::factory()->create(['user_id' => $this->company->id]);
        $this->jobSeeker = User::factory()->create();
        $this->cv = Cv::factory()->create(['user_id' => $this->jobSeeker->id]);
    }

    /**
     * @test
     */
    public function user_can_apply_a_job()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->jobSeeker);
        $resp = $this->post('/api/jobads/' . $this->jobad->id . '/applications', [
            'cv_id' => $this->cv->id
        ])->assertStatus(201);

        $applications = Application::all();
        $userApplications = $this->jobSeeker->applications()->first();

        $this->assertCount(1, $applications);
        $this->assertNotNull($userApplications);
        $this->assertEquals($this->jobad->id, $userApplications->jobad_id);
        $this->assertEquals($this->jobSeeker->id, $userApplications->user_id);
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
    public function user_should_assign_cv_to_his_application_request()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->jobSeeker, 'api');

        $this->post('/api/jobads/' . $this->jobad->id . '/applications', [
            'cv_id' => $this->cv->id,
        ])->assertStatus(201);

        $application = Application::first();

        $this->assertEquals($this->cv->id, $application->cv_id);
        $this->assertEquals($this->jobSeeker->id, $this->cv->user_id);
    }


    /**
     * @test
     */
    public function user_and_jobad_and_cv_resources_should_retrieved_with_application_resource()
    {
        $application = Application::factory()->create();

        $this->actingAs($this->jobSeeker, 'api');

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
                        'cv' => [
                            'data' => [
                                'type' => 'cvs',
                                'id' => $application->cv_id,
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
        $this->actingAs($this->jobSeeker);
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
    public function cv_is_required_to_apply_a_job_else_return_422()
    {
        $this->actingAs($this->jobSeeker, 'api');
        $response = $this->post('/api/jobads/' . $this->jobad->id . '/applications', [])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'code' => 422,
                ]
            ]);
        $errors = json_decode($response->getContent(),true)['errors']['meta'];
        $this->assertArrayHasKey('cv',$errors);
    }

    /**
     * @test
     */
    public function assigning_non_existing_cv_should_cause_cv_model_not_found_exception()
    {
        $this->actingAs($this->jobSeeker, 'api');

        $resp = $this->post('/api/jobads/' . $this->jobad->id . '/applications', [
            'cv_id' => 4565654,
            'jobad_id' => $this->jobad->id
        ])->assertStatus(404);

        $resp->assertJson([
            'errors' => [
                'code' => 404,
                'description' => 'No query results for model [App\\Models\\Cv].',
            ]
        ]);
    }

    /**
     * @test
     */
    public function user_can_see_his_application_status()
    {
        $this->actingAs($this->jobSeeker, 'api');
        $application = Application::factory([
            'cv_id' => $this->cv->id,
            'jobad_id' => $this->jobad->id
        ])->create();

        $this->get('api/jobads/' . $this->jobad->id . '/applications/' . $application->id)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $application->id,
                    'attributes' => [
                        'status' => 0
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function a_jobSeeker_can_apply_the_same_job_only_once()
    {
        $this->actingAs($this->jobSeeker, 'api');

        $application = Application::factory([
            'cv_id' => $this->cv->id,
            'jobad_id' => $this->jobad->id
        ])->create();

        $resp = $this->post('/api/jobads/' . $this->jobad->id . '/applications', [
            'cv_id' => $this->cv->id,
            'jobad_id' => $this->jobad->id
        ])->assertStatus(422);

        $resp->assertJson([
            'errors' => [
                'code' => 422,
                'description' => 'you have already an application for this job'
            ]
        ]);
    }

    /**
     * @test
     */
    public function a_jobSeeker_can_upload_cv_when_he_trying_to_apply_a_job()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->jobSeeker, 'api');

        $sizeInKilobytes = 1000;
        $file = UploadedFile::fake()->create(
            'daniel.pdf', $sizeInKilobytes, 'application/pdf'
        );

        $resp = $this->post('/api/jobads/' . $this->jobad->id . '/applications', [
            'cv_details' => [
                'title' => 'newCv',
                'file' => $file,
            ],
            'jobad_id' => $this->jobad->id
        ])->assertStatus(201);

        $uniqueName = 'cvs/' . $this->jobSeeker->id . '/' . $file->getClientOriginalName();
        Storage::disk('local')->assertExists($uniqueName);

        $cv = Cv::orderByDesc('id')->take(1)->first();
        $application = Application::first();

        $this->assertEquals(2, $application->cv->id);
    }
}
