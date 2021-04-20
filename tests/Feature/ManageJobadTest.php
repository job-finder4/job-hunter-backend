<?php

namespace Tests\Feature;

use App\Events\ApplicationEvaluated;
use App\Events\JobadEvaluated;
use App\Models\Jobad;
use App\Models\User;
use App\Notifications\ApplicationApproved;
use App\Notifications\JobadEvaluationStatus;
use App\Traits\RequestDataForTesting;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ManageJobadTest extends TestCase
{
    use RefreshDatabase, RequestDataForTesting;

    public $jobSeeker;
    public $company;
    public $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->jobSeeker = User::factory()->create();
        $this->company = User::factory()->create();
        $this->admin = User::factory()->create();
    }

    /**
     * @test
     */
    public function admin_can_approve_an_unapproved_jobad()
    {
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authorize::class);
        $this->actingAs($user = User::factory()->create());
        $jobad = Jobad::factory()->unapproved()->create();

        $this->putJson('api/jobads/' . $jobad->id . '/approve')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'attributes' => [
                        'approved_at' => now()->toFormattedDateString()
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function when_admin_evaluate_a_jobad_an_event_should_be_dispatched()
    {
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authorize::class);
        Event::fake();
        $jobad = Jobad::factory()->unapproved()->create();

        $this->putJson('api/jobads/' . $jobad->id . '/approve')->assertStatus(200);

        Event::assertDispatched(JobadEvaluated::class,function ($event) use ($jobad){
            return $event->jobad->id = $jobad->id;
        });

        Event::assertDispatchedTimes(JobadEvaluated::class,1);
    }

    /**
     * @test
     */
    public function when_admin_evaluate_a_jobad_an_email_should_be_sent_to_company_that_own_the_job()
    {
        $this->withoutExceptionHandling();

        Notification::fake();
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authorize::class);
        $this->actingAs($this->company);

        $jobad = Jobad::factory()->create(['user_id'=>$this->company->id]);
        event(new JobadEvaluated($jobad));

        Notification::assertSentTo([$this->company], JobadEvaluationStatus::class,
            function (JobadEvaluationStatus $notification, $channels) use ($jobad) {
                return (
                    $notification->jobad->id === $jobad->id &&
                    $notification->jobad->approved_at === $jobad->approved_at
                );
            });
    }

    /**
     * @test
     */
    public function company_can_view_its_active_and_inactive_jobads()
    {
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authorize::class);
        $this->actingAs($this->company);
        //jobads for another company
        Jobad::factory()->count(2)->create();
        //jobads for authenticated company
        Jobad::factory()->count(2)->create(['user_id' => $this->company->id]);
        Jobad::factory()->unapproved()->count(2)->create(['user_id' => $this->company->id]);
        Jobad::factory()->expired()->count(1)->create(['user_id' => $this->company->id]);

        $this->getJson('api/myjobads')
            ->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    /**
     * @test
     */
    public function admin_can_retrieve_unapproved_jobads()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authorize::class);

        $this->actingAs($user = User::factory()->create());
        $jobads = Jobad::factory()->count(5)->unapproved()->create();

        $this->get('api/admin-jobads?pending')
            ->assertStatus(200)->assertJsonCount(5, 'data');
    }


}
