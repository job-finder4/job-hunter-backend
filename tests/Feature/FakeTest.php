<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Jobad;
use App\Models\Skill;
use App\Models\User;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FakeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function test_search()
    {
//        $this->seed(SkillSeeder::class);
        $this->seed('DatabaseSeeder');
//        Jobad::factory()->count(10)->create();
//            ->each(function ($jobad) {
//                $jobad->skills()->attach(
//                    Skill::inRandomOrder()
//                        ->get()
//                        ->take(rand(1, 5))
//                        ->pluck('id')
//                );
//            });



        $res = Jobad::query()->search('Mana Farm Cons Deve Engin Work Home Car')->get();

    }
}
