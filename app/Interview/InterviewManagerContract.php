<?php


namespace App\Interview;


use App\Models\Interview;
use App\Models\Jobad;
use App\Models\User;
use Illuminate\Http\Request;

interface InterviewManagerContract
{
    public function __construct(SchedulerContract $scheduler,Jobad $jobad);

    public function schedule(array $interviewSettings):void;

    public function reserve(Interview $interview, User $user):Interview;

    public function getAll();
}
