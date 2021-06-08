<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\User;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Kalnoy\Nestedset\AncestorsRelation;
use Tests\TestCase;

class AddSkillsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function an_admin_can_add_new_skills()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');

        $this->post('/api/skills', [
            'name' => 'Laravel'
        ])->assertStatus(201);

        $response = $this->get('api/skills')->assertStatus(200);

        $skill = Skill::first();

        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'skills',
                        'id' => $skill->id,
                        'attributes' => [
                            'name' => $skill->name,
                        ]
                    ]
                ]
            ]

        ]);
    }

    /**
     * @test
     */
    public function a_skill_cannot_be_created_without_a_name_field()
    {
        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');

        $response = $this->post('/api/skills', [
            'name' => ''
        ])->assertStatus(422);

        $skill = Skill::first();
        $this->assertNull($skill);

        $responseString = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('name', $responseString['errors']['meta']);
    }

    /**
     * @test
     */
    public function skills_tree_can_be_build()
    {
        $this->seed(SkillSeeder::class);
        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');

        $skills = Skill::get()->toTree();
        $this->assertTrue(true);
    }


}
