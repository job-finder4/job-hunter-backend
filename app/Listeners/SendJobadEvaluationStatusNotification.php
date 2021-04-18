<?php

namespace App\Listeners;

use App\Events\JobadEvaluated;
use App\Notifications\JobadEvaluationStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendJobadEvaluationStatusNotification
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
     * @param  JobadEvaluated  $event
     * @return void
     */
    public function handle(JobadEvaluated $event)
    {
        if($event->jobad->approved_at){
            $event->jobad->user->notify(new JobadEvaluationStatus($event->jobad));
        }
    }
}
