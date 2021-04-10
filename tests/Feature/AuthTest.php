<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
    }

    /**
     * @test
     */
    public function when_the_user_register_as_jobSeeker_a_jobSeeker_role_should_be_assigend_to_him()
    {

        $this->withoutExceptionHandling();

        $response = $this->post('/api/register/jobseeker', [
            'name' => 'Daniel',
            'email' => 'daniel@gmail.com',
            'password' => 'daniel48',
        ])->assertStatus(201);

        $user = User::first();
        $this->assertNotNull($user);
        $this->assertCount(1, User::all());

        $this->actingAs($user, 'api');
        $response = $this->get('/api/user')->assertStatus(200);

        $this->assertNotEmpty($user->getRoleNames());

        $response->assertJson([
            'data' => [
                'type' => 'users',
                'id' => $user->id,
                'attributes' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => "jobSeeker"
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function when_the_user_register_as_company_a_company_role_should_be_assigend_to_him()
    {

        $this->withoutExceptionHandling();

        $response = $this->post('/api/register/company', [
            'name' => 'Daniel',
            'email' => 'daniel@gmail.com',
            'password' => 'daniel48',
        ])->assertStatus(201);

        $user = User::first();
        $this->assertNotNull($user);
        $this->assertCount(1, User::all());

        $this->actingAs($user, 'api');
        $response = $this->get('/api/user')->assertStatus(200);

        $this->assertNotEmpty($user->getRoleNames());

        $response->assertJson([
            'data' => [
                'type' => 'users',
                'id' => $user->id,
                'attributes' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => "company"
                    ]
                ]
        ]);
    }
}
