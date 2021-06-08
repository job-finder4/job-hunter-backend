<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Jobad;
use App\Models\Skill;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JobadCategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function categories_can_be_returned()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');
        $this->seed(CategorySeeder::class);
        $response = $this->get('api/categories')->assertStatus(200);

        $category1 = Category::orderBy('id')->first();
        $category2 = Category::orderByDesc('id')->first();

        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'categories',
                        'id' => $category1->id,
                        'attributes' => [
                            'name' => $category1->name,
                        ]
                    ],
                    'data' => [
                        'type' => 'categories',
                        'id' => $category2->id,
                        'attributes' => [
                            'name' => $category2->name,
                        ]
                    ]
                ]
            ]
        ]);
    }
}
