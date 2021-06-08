<?php

namespace Tests\Feature;

use App\Models\Jobad;
use App\Models\User;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class PaginationResourceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(Authorize::class);
        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function jobad_must_retrieve_as_paginated_resource()
    {
        $this->withoutExceptionHandling();
        Jobad::factory()->count(8)->create();

        $res = $this->get('api/jobads')
            ->assertStatus(200);
        $res->assertJsonCount(5, 'data')
            ->assertJson([
                'data' => [],
                'link' => [
                    'first_page_url' => 'http://localhost/api/jobads?page=1',
                    'prev_page_url' => null,
                    'next_page_url' => 'http://localhost/api/jobads?page=2',
                ],
                "meta" => [
                    "current_page" => 1,
                    "per_page" => 5,
                ]
            ]);
        $next_page_url = 'api' . Str::after(json_decode($res->getContent())->link->next_page_url, 'api');

        $res = $this->get($next_page_url)->assertStatus(200);

        $res->assertJsonCount(3, 'data')
            ->assertJson([
                'data' => [],
                'link' => [
                    'first_page_url' => 'http://localhost/api/jobads?page=1',
                    'prev_page_url' => 'http://localhost/api/jobads?page=1',
                    'next_page_url' => null,
                ],
                "meta" => [
                    "current_page" => 2,
                    "per_page" => 5,
                ]
            ]);
    }

}
