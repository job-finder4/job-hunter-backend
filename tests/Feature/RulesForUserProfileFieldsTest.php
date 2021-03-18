<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use App\Profile\UserProfile;
use Faker\Provider\Lorem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use phpDocumentor\Reflection\Types\This;
use Tests\TestCase;

class RulesForUserProfileFieldsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function end_date_must_be_after_start_date()
    {

        $this->actingAs($user = User::factory()->create(), 'api');

        $details = Profile::factory()->make(['user_id' => $user->id])->details;
        $details['works_experience'][0]['start_date'] = now()->subYears(2);
        $details['works_experience'][0]['end_date'] = now()->subYears(5);

        $resp = $this->post('api/users/' . $user->id . '/profile', [
            'details' => $details,
        ])->assertStatus(422);

        $this->assertArrayHasKey('end_date', json_decode($resp->getContent(), true)['errors']['meta']);
    }

    /**
     * @test
     */
    /**
     * @test
     */
    public function job_title_must_be_string()
    {

        $this->actingAs($user = User::factory()->create(), 'api');

        $details = Profile::factory()->make(['user_id' => $user->id])->details;
        $details['works_experience'][0]['job_title'] = 1234412;

        $resp = $this->post('api/users/' . $user->id . '/profile', [
            'details' => $details,
        ])->assertStatus(422);

        $this->assertArrayHasKey('job_title', json_decode($resp->getContent(), true)['errors']['meta']);
    }

    /**
     * @test
     */
    public function job_company_name_must_be_string()
    {

        $this->actingAs($user = User::factory()->create(), 'api');

        $details = Profile::factory()->make(['user_id' => $user->id])->details;
        $details['works_experience'][0]['company_name'] = 1234412;
        $resp = $this->post('api/users/' . $user->id . '/profile', [
            'details' => $details,
        ])->assertStatus(422);

        $this->assertArrayHasKey('company_name', json_decode($resp->getContent(), true)['errors']['meta']);
    }

    /**
     * @test
     */
    public function job_title_musnt_exceed_20_character()
    {

        $this->actingAs($user = User::factory()->create(), 'api');

        $details = Profile::factory()->make(['user_id' => $user->id])->details;
        $details['works_experience'][0]['job_title'] = Lorem::paragraph(25);

        $resp = $this->post('api/users/' . $user->id . '/profile', [
            'details' => $details,
        ])->assertStatus(422);

        $this->assertArrayHasKey('job_title', json_decode($resp->getContent(), true)['errors']['meta']);
    }


    /**
     * @test
     */
    public function job_company_name_musnt_exceed_20_character()
    {

        $this->actingAs($user = User::factory()->create(), 'api');

        $details = Profile::factory()->make(['user_id' => $user->id])->details;
        $details['works_experience'][0]['company_name'] = Lorem::paragraph(25);
        $resp = $this->post('api/users/' . $user->id . '/profile', [
            'details' => $details,
        ])->assertStatus(422);

        $this->assertArrayHasKey('company_name', json_decode($resp->getContent(), true)['errors']['meta']);

    }

    /**
     * @test
     */
    public function job_title_is_required_to_create_work_experience()
    {
        $this->actingAs($user = User::factory()->create(), 'api');

        $details = Profile::factory()->make(['user_id' => $user->id])->details;

        $details['works_experience'][0]['job_title'] = '';

        $resp = $this->post('api/users/' . $user->id . '/profile', [
            'details' => $details,
        ])->assertStatus(422);

        $this->assertArrayHasKey('job_title', json_decode($resp->getContent(), true)['errors']['meta']);
    }

    /**
     * @test
     */
    public function company_name_is_required_to_create_work_experience()
    {
        $this->actingAs($user = User::factory()->create(), 'api');

        $details = Profile::factory()->make(['user_id' => $user->id])->details;

        $details['works_experience'][0]['company_name'] = '';

        $resp = $this->post('api/users/' . $user->id . '/profile', [
            'details' => $details,
        ])->assertStatus(422);

        $this->assertArrayHasKey('company_name', json_decode($resp->getContent(), true)['errors']['meta']);
    }

    /**
     * @test
     */
    public function start_date_is_required_to_create_work_experience()
    {
        $this->actingAs($user = User::factory()->create(), 'api');

        $details = Profile::factory()->make(['user_id' => $user->id])->details;

        $details['works_experience'][0]['start_date'] = '';

        $resp = $this->post('api/users/' . $user->id . '/profile', [
            'details' => $details,
        ])->assertStatus(422);

        $this->assertArrayHasKey('start_date', json_decode($resp->getContent(), true)['errors']['meta']);
    }

    /**
     * @test
     */
    public function works_experience_field_if_occured_in_reuest_then_must_be_of_array_and_not_empty()
    {
        $this->actingAs($user = User::factory()->create(), 'api');
        $details = Profile::factory()->make(['user_id' => $user->id])->details;
        $details['works_experience'] = [];

        $resp1 = $this->post('api/users/' . $user->id . '/profile', [
            'details' => $details,
        ]);

        $details['works_experience'] = 'asdf';

        $resp2 = $this->post('api/users/' . $user->id . '/profile', [
            'details' => $details,
        ]);

        $resp1->assertStatus(422);
        $resp2->assertStatus(422);
        $this->assertArrayHasKey('works_experience', json_decode($resp1->getContent(), true)['errors']['meta']);
        $this->assertArrayHasKey('works_experience', json_decode($resp2->getContent(), true)['errors']['meta']);
    }

    /**
     * @test
     */
    public function educations_field_if_occured_in_reuest_then_must_be_of_array_and_not_empty()
    {
        $this->actingAs($user = User::factory()->create(), 'api');

        $details = Profile::factory()->make(['user_id' => $user->id])->details;

        $details['educations'] = [];
        $resp1 = $this->post('api/users/' . $user->id . '/profile', [
            'details' => $details,
        ]);
        $details['educations'] = 'asd';
        $resp2 = $this->post('api/users/' . $user->id . '/profile', [
            'details' => $details,
        ]);

        $resp1->assertStatus(422);
        $resp2->assertStatus(422);
        $this->assertArrayHasKey('educations', json_decode($resp1->getContent(), true)['errors']['meta']);
        $this->assertArrayHasKey('educations', json_decode($resp2->getContent(), true)['errors']['meta']);
    }

    /**
     * @test
     */
    public function languages_field_if_occured_in_reuest_then_must_be_of_array_and_not_empty()
    {
        $this->actingAs($user = User::factory()->create(), 'api');

        $details = Profile::factory()->make(['user_id' => $user->id])->details;

        $details['languages'] = [];
        $resp1 = $this->post('api/users/' . $user->id . '/profile', [
            'details' => $details,
        ]);
        $details['languages'] = 'asd';
        $resp2 = $this->post('api/users/' . $user->id . '/profile', [
            'details' => $details,
        ]);

        $resp1->assertStatus(422);
        $resp2->assertStatus(422);
        $this->assertArrayHasKey('languages', json_decode($resp1->getContent(), true)['errors']['meta']);
        $this->assertArrayHasKey('languages', json_decode($resp2->getContent(), true)['errors']['meta']);
    }


    public function user_profile_languages_must_unique()
    {

    }

}
