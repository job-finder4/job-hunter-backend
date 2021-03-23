<?php

namespace Tests\Feature;

use App\Models\User;
use App\Traits\RequestDataForTesting;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfilePermissionTest extends TestCase
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
    public function company_is_not_authorized_to_create_profile()
    {
        $user = $this->company;
        $this->actingAs($user);
        $this->post('api/users/' . $user->id . '/profile', $this->getProfileDetails())
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function jobSeeker_authorized_to_create_profile()
    {
        $user = $this->jobSeeker;
        $this->actingAs($user);
        $this->post('api/users/' . $user->id . '/profile', $this->getProfileDetails())
            ->assertStatus(201);
    }

    /**
     * @test
     */
    public function admin_authorized_to_create_profile()
    {
        $user = $this->admin;
        $this->actingAs($user);
        $this->post('api/users/' . $user->id . '/profile', $this->getProfileDetails())
            ->assertStatus(201);
    }

//    --------------------------------update profile--------------------------------

    /**
     * @test
     */
    public function company_is_not_authorized_to_update_profile()
    {
        $user = $this->company;
        $this->actingAs($user);
        $profileResponse = response($user->addProfileDetails($this->getProfileDetails()));
        $profile = json_decode($profileResponse->getContent());

        $education = $profile->details->educations[0];
        $education->degree = 'DR';

        $details = [
            'educations' => [$education],
        ];

        $this->putJson('/api/users/' . $user->id . '/profile/' . $profile->id, [
            'details' => $details,
            'visible' => false
        ])->assertStatus(403);
    }


    /**
     * @test
     */
    public function job_seeker_is_authorized_to_update__his_profile()
    {
        $user = $this->jobSeeker;
        $this->actingAs($user);
        $profileResponse = response($user->addProfileDetails($this->getProfileDetails()));
        $profile = json_decode($profileResponse->getContent());

        $education = $profile->details->educations[0];
        $education->degree = 'DR';

        $details = [
            'educations' => [$education],
        ];

        $this->putJson('/api/users/' . $user->id . '/profile/' . $profile->id, [
            'details' => $details,
            'visible' => false
        ])->assertStatus(200);
    }

    /**
     * @test
    */
    public function job_seeker_is_not_authorized_to_update_profile_belongs_to_another_user()
    {
        $profileOwner = User::factory()->create();
        $profileOwner->assignRole('jobSeeker');
        $this->actingAs($profileOwner);

        $profileResponse = response($profileOwner->addProfileDetails($this->getProfileDetails()));
        $profile = json_decode($profileResponse->getContent());

        $user = $this->jobSeeker;
        $this->actingAs($user);

        $education = $profile->details->educations[0];
        $education->degree = 'DR';
        $details = [
            'educations' => [$education],
        ];

        $this->putJson('/api/users/' . $profileOwner->id . '/profile/' . $profile->id, [
            'details' => $details,
            'visible' => false
        ])->assertStatus(403);
    }
//    ----------------------------------view profile---------------------------------------
    public function job_seeker_is_authorized_to_view_his_profile()
    {
        $user = $this->jobSeeker;
        $this->actingAs($this->jobSeeker);
        $user->addProfileDetails($this->getProfileDetails());

    }

}
