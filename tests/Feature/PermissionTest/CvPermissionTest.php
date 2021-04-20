<?php

namespace Tests\Feature;

use App\Models\Cv;
use App\Models\User;
use App\Traits\RequestDataForTesting;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CvPermissionTest extends TestCase
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

//    /**
//     * @test
//    */
//    public function user_is_authorize_to_download_his_cv()
//    {
//
//        $user = $this->jobSeeker;
//        $cv = Cv::factory()->create(['user_id' => $user->id]);
//
//        $this->get('api/cvs/' . $cv->id . '/download')->assertStatus(200);
//    }

}
