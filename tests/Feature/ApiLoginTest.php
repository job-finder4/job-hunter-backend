<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiLoginTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        \Artisan::call('migrate',['-vvv' => true]);
        \Artisan::call('passport:install',['-vvv' => true]);
        \Artisan::call('db:seed',['-vvv' => true]);
    }

    /**
     * @test
     */
    public function testApiLogin() {
        $this->withoutExceptionHandling();

        $this->post('/api/register',[
            'name' => 'das',
            'email' => 'admin@gmail.com',
            'password' => 'admin'
        ])->assertStatus(201);
    }
    /**
     * @group apilogintests
     */
    public function testApiLogin2() {
        $this->withoutExceptionHandling();

        $body = [
            'username' => 'admin@admin.com',
            'password' => 'admin'
        ];

        $this->json('POST','/api/login',$body,['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['token_type','expires_in','access_token','refresh_token']);
    }

}
