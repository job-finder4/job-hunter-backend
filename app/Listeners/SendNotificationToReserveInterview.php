<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\ReserveInterview;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNotificationToReserveInterview
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $jobad = $event->jobad;
        $jobseekers = $jobad->applications()
            ->where('status', 1)
            ->get()
            ->load('cv.user')
            ->pluck('user')
            ->flatten();

        $jobseekers->each(function ($jobseeker) use ($jobad, $event) {
            $jobseeker->notify(new ReserveInterview($jobad, $event->message));
        });
    }
}
