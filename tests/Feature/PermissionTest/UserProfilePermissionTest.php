<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use App\Profile\UserProfile;
use App\Traits\RequestDataForTesting;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use phpDocumentor\Reflection\Types\Boolean;
use Tests\TestCase;

class UserProfilePermissionTest extends TestCase
{

    use RefreshDatabase, RequestDataForTesting;

    public $jobSeeker;
    public $company;
    public $admin;

    /**
     * @return array
     */
    public function createProfileTo(User $user = null,bool $visible = true): array
    {
        $profileOwner = !!$user ? $user : User::factory()->create();
        $profileOwner->assignRole('jobSeeker');
        $this->actingAs($profileOwner);

        $details = UserProfile::make($this->getProfileDetails()['details']);

        $profile = auth()->user()->profile()->create([
            'details' => $details,
            'visible' => $visible
        ]);

        $profileResponse = response($profile);

        $profile = json_decode($profileResponse->getContent());
        return array($profileOwner, $profile);
    }

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
        list($profileOwner, $profile) = $this->createProfileTo();

        $user = $this->company;
        $this->actingAs($user);

        $education = $profile->details->educations[0];
        $education->degree = 'DR';

        $details = [
            'educations' => [$education],
        ];

        $this->putJson('/api/users/' . $user->id . '/profile', [
            'details' => $details,
            'visible' => false
        ])->assertStatus(403);
    }


    /**
     * @test
     */
    public function job_seeker_is_authorized_to_update_his_profile()
    {
        $user = $this->jobSeeker;
        $this->actingAs($user);

        list($profileOwner, $profile) = $this->createProfileTo($user);

        $education = $profile->details->educations[0];
        $education->degree = 'DR';

        $details = [
            'educations' => [$education],
        ];

        $this->putJson('/api/users/' . $user->id . '/profile', [
            'details' => $details,
            'visible' => false
        ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function job_seeker_is_not_authorized_to_update_profile_belongs_to_another_user()
    {
        list($profileOwner, $profile) = $this->createProfileTo();
        $user = $this->jobSeeker;
        $this->actingAs($user);

        $education = $profile->details->educations[0];
        $education->degree = 'DR';
        $details = [
            'educations' => [$education],
        ];

        $this->putJson('/api/users/' . $profileOwner->id . '/profile', [
            'details' => $details,
            'visible' => false
        ])->assertStatus(403);
    }

//    ----------------------------------view profile---------------------------------------

    /**
     * @test
     */
    public function job_seeker_is_not_authorized_to_view_another_profiles()
    {
        list($profileOwner, $profile) = $this->createProfileTo();

        $user = $this->jobSeeker;
        $this->actingAs($user);
        $this->get('api/users/' . $profileOwner->id . '/profile')
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function company_is_authorized_to_view_jobseeker_profiles_if_it_public()
    {
        list($profileOwner, $profile) = $this->createProfileTo();

        $user = $this->company;
        $this->actingAs($user);
        $this->get('api/users/' . $profileOwner->id . '/profile')
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function company_is_not_authorized_to_view_jobseeker_profiles_if_it_private()
    {
        list($profileOwner, $profile) = $this->createProfileTo(User::factory()->create(),false);

        $user = $this->company;
        $this->actingAs($user);
        $this->get('api/users/' . $profileOwner->id . '/profile')
            ->assertStatus(403);
    }


    /**
     * @test
     */
    public function job_seeker_is_authorized_to_view_his_profile()
    {
        $user = $this->jobSeeker;
        $this->actingAs($this->jobSeeker);
        list($profileOwner, $profile) = $this->createProfileTo($user);
        $this->get('api/users/' . $user->id . '/profile')
            ->assertStatus(200);
    }

}
