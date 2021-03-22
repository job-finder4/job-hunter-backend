<?php

namespace Tests\Feature;

use App\Models\Jobad;
use App\Models\Skill;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Arr;
use Tests\TestCase;

class UpdateJobTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * @group dani
     */
    use RefreshDatabase;

    public function getJobDetails()
    {
        $this->seed(SkillSeeder::class);
        $skills = Skill::inRandomOrder()->get()->take(2);
        return [
            'title' => 'ceo',
            'min_salary' => 1000,
            'max_salary' => 1500,
            'description' => 'this job require experience in ceo in big company',
            'location' => 'london',
            'job_type' => 'remote',
            'job_time' => 'part_time',
            'skills' => [
                ['id' => $skills[0]->id],
                ['id' => $skills[1]->id]
            ],
        ];
    }

    /**
     * @test
     */
    public function a_company_can_update_job_ad()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');

        $jobad = Jobad::factory()->create();

        $response = $this->put('/api/jobads/' . $jobad->id, $this->getJobDetails())
            ->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $jobad->id,
                'type' => 'jobads',
                'attributes' => [
                    'title' => 'ceo',
                    'location' => 'london',
                    'description' => 'this job require experience in ceo in big company',
                    'min_salary' => 1000,
                    'max_salary' => 1500,
                    'job_time' => 'part_time',
                    'job_type' => 'remote',
                ],
            ]
        ]);
    }

//    /**
//     * @test
//     */
//    public function no_change_update_should_return_204()
//    {
//        $this->withoutExceptionHandling();
//
//        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');
//
//        $jobad = Jobad::factory()->create(Arr::except($this->getJobDetails(), 'skills'));
//        $jobad->skills()->attach(Skill::where('id',1)->first()->id);
//
//        $response = $this->put('/api/jobads/' . $jobad->id, $this->getJobDetails())
//            ->assertStatus(204);
//    }

    /**
     * @test
     */
    public function a_job_ad_cannot_be_updated_with_null_title()
    {
        $this->seed(SkillSeeder::class);

        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');

        $jobad = Jobad::factory()->create();

        $response = $this->put('/api/jobads/' . $jobad->id,
            [
                'title' => null,
            ])
            ->assertStatus(422);
    }

}
