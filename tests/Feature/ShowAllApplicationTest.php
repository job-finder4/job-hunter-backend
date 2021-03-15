<?php

namespace Tests\Feature;

use App\Http\Resources\Cv as CvResource;
use App\Http\Resources\Jobad as JobadResource;
use App\Http\Resources\User as UserResource;
use App\Models\Application;
use App\Models\Cv;
use App\Models\Jobad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ShowAllApplicationTest extends TestCase
{

    use RefreshDatabase;


    public $jobSeeker;
    public $company;
    public $cv;
    public $jobad;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->jobSeeker = User::factory()->create();
        $this->company = User::factory()->create();
        $this->cv = Cv::factory()->create(['user_id' => $this->jobSeeker->id]);
        $this->jobad = Jobad::factory()->create([
            'user_id' => $this->company->id
        ]);
    }

    /**
     * @test
     */
    public function user_can_show_all_his_applications()
    {
        $this->actingAs($this->jobSeeker);
        Application::factory()->count(5)->create(['cv_id' => $this->cv->id]);
        $this->get('/api/users/' . $this->jobSeeker->id . '/applications')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'data' =>
                            [
                                'id',
                                'type',
                                'attributes' => [
                                    'user', 'jobad', 'cv', 'status', 'applied_at'
                                ]
                            ]
                    ]
                ],
                'links' => [
                    'self'
                ]
            ])->assertJsonCount(5, 'data');
    }

    /**
     * @test
     */
    public function company_can_show_all_applications_on_his_jobad()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->company, 'api');
        Application::factory()->count(5)->create([
            'jobad_id' => $this->jobad->id
        ]);

        $this->get('api/jobads/' . $this->jobad->id . '/applications')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'data' =>
                            [
                                'id',
                                'type',
                                'attributes' => [
                                    'user', 'jobad', 'cv', 'status', 'applied_at'
                                ]
                            ]
                    ]
                ],
                'links' => [
                    'self'
                ]
            ])
            ->assertJsonCount(5, 'data');
    }

    /**
     * @test
     */
    public function jobad_retrieved_with_applied_at_property_to_determine_if_jobseeker_applied_before()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->jobSeeker);
        Application::factory()->create([
            'cv_id' => $this->cv->id,
            'jobad_id' => $this->jobad->id
        ]);
        $this->get('api/jobads')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'data' => [
                            'attributes' => [
                                'applied_at' => now()->toFormattedDateString()
                            ]
                        ]
                    ]
                ]
            ]);

    }


}
