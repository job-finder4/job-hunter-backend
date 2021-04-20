<?php

namespace App\Listeners;

use App\Events\ApplicationEvaluated;
use App\Notifications\ApplicationApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendApplicationStatusNotification
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
     * @param  object  $event
     * @return void
     */
    public function handle(ApplicationEvaluated $event)
    {
        if($event->application->status==1){
            $event->application->cv->user->notify(new ApplicationApproved($event->application));
        }
    }
}
