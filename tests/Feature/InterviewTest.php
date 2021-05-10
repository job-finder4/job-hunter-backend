<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Cv;
use App\Models\Interview;
use App\Models\Jobad;
use App\Models\User;
use Carbon\Carbon;
use Database\Factories\ApplicationFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InterviewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_interview_and_sort_interviews_correctly()
    {
        $this->actingAs($user = User::factory()->create());

        $jobad = Jobad::factory()->create(['user_id' => $user->id]);

        $applications = Application::factory()
            ->approved()
            ->count(4)
            ->create([
                'jobad_id' => $jobad->id,
            ]);

        $payload = [
            'days' => [
                [
                    'date' => now()->addDays(1)->toDateString(),
                    'start_time' => '9:00',
                    'end_time' => '11:00',
                ],
                [
                    'date' => now()->addDays(2)->toDateString(),
                    'start_time' => '10:00',
                    'end_time' => '11:00'
                ]
            ],
            'duration' => 30,
            'break' => 10,
            'contact_info' => 'contact us on skype gobran@net.com',
            'message' => 'we hop you ti participate in our interview'
        ];

        $resp = $this->post("api/jobads/$jobad->id/interviews", $payload)
            ->assertStatus(200);

        $resp->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'interviews',
                        'id' => 1,
                        'attributes' => [
                            'start_date' => now()->addDays(1)->setTime(9, 0)->toDateTimeString(),
                            'end_date' => now()->addDays(1)->setTime(9, 30)->toDateTimeString(),
                            'contact_info' => 'contact us on skype gobran@net.com'
                        ]
                    ]
                ],
                [
                    'data' => [
                        'type' => 'interviews',
                        'id' => 2,
                        'attributes' => [
                            'start_date' => now()->addDays(1)->setTime(9, 40)->toDateTimeString(),
                            'end_date' => now()->addDays(1)->setTime(10, 10)->toDateTimeString(),
                            'contact_info' => 'contact us on skype gobran@net.com'
                        ]
                    ]
                ],
                [
                    'data' => [
                        'type' => 'interviews',
                        'id' => 3,
                        'attributes' => [
                            'start_date' => now()->addDays(1)->setTime(10, 20)->toDateTimeString(),
                            'end_date' => now()->addDays(1)->setTime(10, 50)->toDateTimeString(),
                            'contact_info' => 'contact us on skype gobran@net.com'
                        ]
                    ]
                ],
                [
                    'data' => [
                        'type' => 'interviews',
                        'id' => 4,
                        'attributes' => [
                            'jobad_id' => $jobad->id,
                            'user_id' => null,
                            'start_date' => now()->addDays(2)->setTime(10, 0)->toDateTimeString(),
                            'end_date' => now()->addDays(2)->setTime(10, 30)->toDateTimeString(),
                            'contact_info' => 'contact us on skype gobran@net.com'
                        ]
                    ]
                ]
            ],
            'links' => [
                'self' => "api/jobads/$jobad->id/interviews"
            ]
        ]);
    }

    /**
     * @test
     */
    public function interviews_interval_is_not_big_as_we_want()
    {
        $this->actingAs($user = User::factory()->create());

        $jobad = Jobad::factory()->create(['user_id' => $user->id]);

        $applications = Application::factory()
            ->approved()
            ->count(4)
            ->create([
                'jobad_id' => $jobad->id,
            ]);

        $payload = [
            'days' => [
                [
                    'date' => now()->addDays(1)->toDateString(),
                    'start_time' => '9:00',
                    'end_time' => '11:00',
                ],
                [
                    'date' => now()->addDays(2)->toDateString(),
                    'start_time' => '10:00',
                    'end_time' => '10:20'
                ]
            ],
            'duration' => 30,
            'break' => 10,
            'contact_info' => 'contact us on skype gobran@net.com',
            'message' => 'we hop you ti participate in our interview'
        ];

        $resp = $this->post("api/jobads/$jobad->id/interviews", $payload)
            ->assertStatus(409);
    }


    /**
     * @test
     */
    public function user_reserve_an_interview()
    {
        $jobad = Jobad::factory()->create();
        $this->actingAs($jobseeker = User::factory()->create());

        $application = Application::factory()
            ->approved()
            ->create([
                'jobad_id' => $jobad->id,
                'cv_id' => Cv::factory()->create(['user_id' => $jobseeker->id])->id
            ]);

        $interview = Interview::factory()->unreserved()->create(['jobad_id' => $jobad->id]);

        $resp = $this->put("api/jobads/$jobad->id/interviews/$interview->id/reserve")
            ->assertStatus(200);
        $interview->refresh();

        $this->assertEquals($jobseeker->id, $interview->user_id);

        $resp->assertJson([
            'data' => [
                'type' => 'interviews',
                'id' => 1,
                'attributes' => [
                    'user_id' => $jobseeker->id
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function user_should_have_approved_application_to_reserve_an_interview()
    {
        $jobad = Jobad::factory()->create();
        $this->actingAs($jobseeker = User::factory()->create());

        Application::factory()
            ->rejected()
            ->create([
                'jobad_id' => $jobad->id,
                'cv_id' => Cv::factory()->create(['user_id' => $jobseeker->id])->id
            ]);

        $interview = Interview::factory()
            ->unreserved()
            ->create(['jobad_id' => $jobad->id]);

        $resp = $this->put("api/jobads/$jobad->id/interviews/$interview->id/reserve")
            ->assertStatus(403);

        $interview->refresh();

        $this->assertEquals($interview->user_id, null);

        $resp->assertJson([
            'errors' => [
                'code' => 403,
                'description' => 'you are not permitted to reserve an interview for this job',
            ]
        ]);
    }

    /**
     * @test
     */
    public function if_user_reserve_an_pre_reserved_interview_then_404_most_return()
    {
        $jobad = Jobad::factory()->create();
        $this->actingAs($jobseeker = User::factory()->create());

        Application::factory()
            ->approved()
            ->create([
                'jobad_id' => $jobad->id,
                'cv_id' => Cv::factory()->create(['user_id' => $jobseeker->id])->id
            ]);

        $interview = Interview::factory()
            ->create(['jobad_id' => $jobad->id]);

        $resp = $this->put("api/jobads/$jobad->id/interviews/$interview->id/reserve")
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function after_schedule_an_interviews_an_event_should_be_fired()
    {

    }

}
