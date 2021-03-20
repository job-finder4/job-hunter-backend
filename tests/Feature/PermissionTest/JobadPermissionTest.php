<?php

namespace Tests\Feature;

use App\Models\User;
use App\Traits\RequestDataForTesting;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JobadPermissionTest extends TestCase
{
    use RefreshDatabase,RequestDataForTesting;

    public $jobSeeker;
    public $company;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->jobSeeker = User::factory()->create()->assignRole('company');
        $this->company = User::factory()->create()->assignRole('jobSeeker');
    }

    /**
     * @test
     */
    public function company_authorized_to_create_jobad()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->company);
        $this->post('api/jobads', $this->getJobDetails())
            ->assertStatus(201);
    }

    /**
     * @test
     */
    public function job_seeker_is_not_authorized_to_create_jobad()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->company);
        $this->post('api/jobads', $this->getJobDetails())
            ->assertStatus(403);
    }

}
