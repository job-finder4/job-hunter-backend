<?php

namespace Tests\Feature;

use App\Models\Jobad;
use App\Models\Skill;
use App\Models\User;
use Database\Seeders\SkillSeeder;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchJobTest extends TestCase
{

    use RefreshDatabase;

    protected $jobSeeker;
    protected $webDevelopmentSkill;
    protected $ITSkill;
    protected $laravelSkill;
    protected $consultingSkill;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SkillSeeder::class);
        $this->withoutMiddleware(Authorize::class);
        $this->jobSeeker = User::factory()->create();

        $this->consultingSkill = Skill::where('name', 'Consulting')->first();
        $this->ITSkill = Skill::where('name', 'IT')->first();
        $this->webDevelopmentSkill = Skill::where('name', 'Web Development')->first();
        //create new skill
        $laravelSkill = Skill::create(['name' => 'laravel']);
        $this->webDevelopmentSkill->appendNode($laravelSkill);
        $this->laravelSkill = Skill::whereName('laravel')->firstOrFail();
    }

    /**
     * @test
     */
    public function test_what_search_should_return()
    {
        $this->withoutExceptionHandling();

        $user = $this->jobSeeker;
        $this->actingAs($user);

        //create jobad must be retrieved in search
        $jobad1 = Jobad::factory()->create();
        $jobad1->skills()->attach($this->ITSkill->id);

        $jobad2 = Jobad::factory()->create();
        $jobad2->skills()->attach($this->webDevelopmentSkill->id);

        $jobad3 = Jobad::factory()->create();
        $jobad3->skills()->attach($this->laravelSkill->id);

        $jobad4 = Jobad::factory()->create(['title' => 'developer']);

        $jobad5 = Jobad::factory()->create(['location' => 'london']);

        $jobad6 = Jobad::factory()->create(['min_salary' => 10000, 'max_salary' => 20000]);

        $jobad7 = Jobad::factory()->create(['title' => 'developer']);
        $jobad7->skills()->attach($this->laravelSkill->id);


        //create undesirable jobad
        $undesirableJobad = Jobad::factory()->create();
        $undesirableJobad->skills()->attach($this->consultingSkill->id);

        $resp = $this->call('GET', 'api/search-job', [
            'skill' => 'web development',
            'job_title' => 'developer',
            'location' => 'london',
            'salary' => 15000
        ])
            ->assertStatus(200);

        $resp->assertJson([
            'data' => [
                [
                    'data' => [
                        'id' => $jobad7->id
                    ]
                ],
                [
                    'data' => [
                        'id' => $jobad2->id
                    ]
                ],
                [
                    'data' => [
                        'id' => $jobad3->id
                    ]
                ],
                [
                    'data' => [
                        'id' => $jobad1->id
                    ]
                ],
                [
                    'data' => [
                        'id' => $jobad4->id
                    ]
                ],
                [
                    'data' => [
                        'id' => $jobad5->id
                    ]
                ],
                [
                    'data' => [
                        'id' => $jobad6->id
                    ]
                ]
            ]
        ])
            ->assertJsonMissing([
                'data' => [
                    [
                        'data' => [
                            'id' => $undesirableJobad->id
                        ]
                    ]
                ]
            ]);
    }

}
