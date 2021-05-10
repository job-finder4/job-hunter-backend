<?php

namespace App\Events;

use App\Models\Interview;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InterviewReserved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $interview;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($interview)
    {
        $this->interview = $interview;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("jobads.{$this->interview->jobad_id}.interviews");
    }

    public function broadcastAs()
    {
        return 'InterviewReserved';
    }
}
