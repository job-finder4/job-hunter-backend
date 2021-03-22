<?php

namespace Tests\Feature;

use App\Models\Jobad;
use App\Models\Skill;
use App\Models\User;
use App\Traits\RequestDataForTesting;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobadPermissionTest extends TestCase
{
    use RefreshDatabase, RequestDataForTesting;

    public $jobSeeker;
    public $company;
    public $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->jobSeeker = User::factory()->create()->assignRole('jobSeeker');
        $this->company = User::factory()->create()->assignRole('company');
        $this->admin = User::factory()->create()->assignRole('admin');
    }

    /**
     * @test
     */
    public function company_authorized_to_create_jobad()
    {
        $this->actingAs($this->company);
        $this->postJson('api/jobads', $this->getJobDetails())
            ->assertStatus(201);
    }

    /**
     * @test
     */
    public function job_seeker_is_not_authorized_to_create_jobad()
    {
        $this->actingAs($this->jobSeeker);
        $this->postJson('api/jobads', $this->getJobDetails())
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function admin_can_create_jobad()
    {
        $this->actingAs($this->admin);
        $this->postJson('api/jobads', $this->getJobDetails())
            ->assertStatus(201);
    }

//    ----------------------------------update jobad--------------------------------------

    /**
     * @test
     */
    public function job_seeker_is_not_authorize_to_update_a_jobad()
    {
        $this->actingAs($this->jobSeeker);
        $jobad = Jobad::factory()->create();

        $this->putJson('api/jobads/' . $jobad->id, [
            'title' => 'new job title',
            'skills' => [Skill::factory()->create()]
        ])->assertStatus(403);
    }

    /**
     * @test
     */
    public function company_can_update_a_jobad()
    {
        $this->actingAs($this->company);
        $jobad = Jobad::factory()->create(['user_id' => $this->company->id]);

        $this->putJson('api/jobads/' . $jobad->id, [
            'title' => 'new job title',
            'skills' => [Skill::factory()->create()]
        ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function company_cant_update_jobad_belongs_to_another_company()
    {
        $this->actingAs($this->company);
        $jobad = Jobad::factory()->create();

        $this->putJson('api/jobads/' . $jobad->id, [
            'title' => 'new job title',
            'skills' => [Skill::factory()->create()]
        ])->assertStatus(403);
    }
//    -----------------------------------------view jobads---------------------------------------

    /**
     * @test
     */
    public function any_visitor_can_view_jobads()
    {
        $this->withoutExceptionHandling();
        Jobad::factory()->count(8)->create();
        $this->getJson('api/jobads')
            ->assertStatus(200);
    }
//    ----------------------------------------approve jobad----------------------------------

    /**
     * TODO you must return this to another test class(eg: JobadsTest)
     * @test
     */
    public function admin_can_approve_an_unapproved_jobad()
    {
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authorize::class);
        $this->actingAs($user = User::factory()->create());
        $jobad = Jobad::factory()->unapproved()->create();

        $this->putJson('api/jobads/' . $jobad->id . '/approve')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'attributes' => [
                        'approved_at' => now()->toFormattedDateString()
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function company_can_view_its_active_and_inactive_jobads()
    {
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authorize::class);
        $this->actingAs($this->company);
        //jobads for another company
        Jobad::factory()->count(2)->create();
        //jobads for authenticated company
        Jobad::factory()->count(2)->create(['user_id' => $this->company->id]);
        Jobad::factory()->unapproved()->count(2)->create(['user_id' => $this->company->id]);
        Jobad::factory()->expired()->count(1)->create(['user_id' => $this->company->id]);

        $this->getJson('api/myjobads')
            ->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }
//  -------------------------------------------------------------------------------------

    /**
     * @test
     */
    public function admin_is_authorize_to_approve_a_unapproved_jobad()
    {
        $this->actingAs($this->admin);
        $jobad = Jobad::factory()->unapproved()->create();

        $this->putJson('api/jobads/' . $jobad->id . '/approve')
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function company_is_not_authorize_to_approve_a_jobad()
    {
        $this->actingAs($this->company);
        $jobad = Jobad::factory()->unapproved()->create(['user_id' => $this->company->id]);

        $this->putJson('api/jobads/' . $jobad->id . '/approve')
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function jobseeker_is_not_authorize_to_approve_a_jobad()
    {
        $this->actingAs($this->jobSeeker);
        $jobad = Jobad::factory()->unapproved()->create();

        $this->putJson('api/jobads/' . $jobad->id . '/approve')
            ->assertStatus(403);
    }

//    ---------------------view jobads that belongs to authenticated company------------------------------
    /**
     * @test
     */
    public function a_jobSeeker_is_not_authorized_to_use_view_company_jobads_action()
    {
        $this->actingAs($this->jobSeeker);
        $this->getJson('api/myjobads')
            ->assertStatus(403);
    }



    /**
     * @test
    */
    public function a_company_is_authorized_to_view_its_all_jobads()
    {
        $this->actingAs($this->company);
        $this->getJson('api/myjobads')
            ->assertStatus(200);
    }
}
