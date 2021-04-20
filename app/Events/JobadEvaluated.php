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

class JobadEvaluated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jobad;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Jobad $jobad)
    {
        $this->jobad=$jobad;
    }


}
