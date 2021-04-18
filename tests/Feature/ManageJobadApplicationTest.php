<?php

namespace Tests\Feature;

use App\Events\ApplicationEvaluated;
use App\Models\Application;
use App\Models\Cv;
use App\Models\Jobad;
use App\Models\User;
use App\Notifications\ApplicationApproved;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ManageJobadApplicationTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->company = User::factory()->create();
        $this->jobad = Jobad::factory()->create(['user_id' => $this->company->id]);
        $this->jobSeeker = User::factory()->create();
        $this->cv = Cv::factory()->create(['user_id' => $this->jobSeeker->id]);
        $this->application = Application::factory()->create([
            'cv_id' => $this->cv->id,
            'jobad_id' => $this->jobad->id
        ]);
        $this->actingAs($this->company);
    }

    /**
     * @test
     */
    public function company_can_evaluate_user_applications_on_its_jobad()
    {
        $this->withoutExceptionHandling();
        $resp = $this->put('/api/jobads/' . $this->jobad->id . '/applications/' . $this->application->id . '/manage', [
            'status' => 1
        ])->assertStatus(200);

        $application = $this->application->fresh();

        $this->assertEquals(1, $application->status);
        $resp->assertJson([
            'data' => [
                'id' => $application->id,
                'attributes' => [
                    'status' => 1
                ]
            ]
        ]);
    }

    /**
     * @test
    */
    public function an_event_should_be_go_after_a_company_evaluate_a_jobseeker()
    {
        Event::fake();
        $application = $this->application;
        $this->put('/api/jobads/' . $this->jobad->id . '/applications/' . $application->id . '/manage', [
            'status' => 1
        ])->assertStatus(200);

        Event::assertDispatched(ApplicationEvaluated::class,function ($event) use ($application){
            return $event->application->id = $application->id;
        });

        Event::assertDispatchedTimes(ApplicationEvaluated::class,1);
    }

    /**
     * @test
     */
    public function an_email_should_be_sent_to_jobSeeker_when_application_is_approved()
    {
        Notification::fake();
        $application = $this->application;
        $application->status =1;
        event(new ApplicationEvaluated($application));

        Notification::assertSentTo([$this->jobSeeker],ApplicationApproved::class,
            function (ApplicationApproved $notification, $channels) use ($application) {
                return $notification->application->id === $application->id;
            });
    }

}
