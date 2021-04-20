<?php

namespace App\Jobs;

use App\Models\Jobad;
use App\Models\User;
use App\Notifications\RecommendedJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class RecommendUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jobad;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Jobad $jobad)
    {
        $this->jobad = $jobad;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $skills = $this->jobad->load('skills')->skills;
        $users = $skills->load('users')->flatMap(function ($skill){
            return $skill->users;
        })->unique('id');

        Notification::send($users,new RecommendedJob($this->jobad));
    }
}
