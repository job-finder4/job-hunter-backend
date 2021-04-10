<?php

namespace Tests\Feature;

use App\Models\Jobad;
use App\Models\User;
use App\Traits\RequestDataForTesting;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageJobadTest extends TestCase
{
    use RefreshDatabase, RequestDataForTesting;

    public $jobSeeker;
    public $company;
    public $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->jobSeeker = User::factory()->create();
        $this->company = User::factory()->create();
        $this->admin = User::factory()->create();
    }

    /**
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

    /**
     * @test
     */
    public function admin_can_retrieve_unapproved_jobads()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authorize::class);

        $this->actingAs($user = User::factory()->create());
        $jobads = Jobad::factory()->count(5)->unapproved()->create();

        $this->get('api/admin-jobads?filter=pending')
            ->assertStatus(200)->assertJsonCount(5,'data');
    }


}
