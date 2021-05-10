<?php

namespace App\Events;

use App\Models\Jobad;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InterviewsWasScheduled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jobad;
    public $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Jobad $jobad,$message)
    {
        $this->jobad = $jobad;
        $this->message = $message;
    }
}
