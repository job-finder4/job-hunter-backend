<?php

namespace App\Http\Controllers;

use App\Events\InterviewReserved;
use App\Events\InterviewsWasScheduled;
use App\Http\Resources\Interview as InterviewResource;
use App\Http\Resources\InterviewCollection;
use App\Interview\InterviewManagerContract;
use App\Interview\SchedulerContract;
use App\Models\Interview;
use App\Models\Jobad;
use Illuminate\Http\Request;


class JobadInterviewController extends Controller
{

    public function checkInterviewAble(SchedulerContract $scheduler, Jobad $jobad)
    {
        $scheduler->checkIfJobIsAbleToInterviewScheduling();
        $interviewers_count = $jobad->applications()->where('status', 1)->count();
        return response(['interviewers_count' => $interviewers_count], 200);
    }

    public function store(Request $request, InterviewManagerContract $interviewManager, Jobad $jobad)
    {

        $interviewManager->schedule([
            'days' => $request->days,
            'duration' => $request->duration,
            'break' => $request->break,
            'contact_info' => $request->contact_info
        ]);
        event(new InterviewsWasScheduled($jobad, $request->message));
        return response()->json(new InterviewCollection($jobad->interviews()->get()));
    }

    public function book(Request $request,
                         InterviewManagerContract $interviewManager,
                         Jobad $jobad,
                         Interview $interview)
    {
        $user = $request->user();

        $interview = $interviewManager->reserve($interview, $user);

        $newInterview = new InterviewResource($interview);

        broadcast(new InterviewReserved($newInterview))->toOthers();

        return response()->json(new InterviewResource($newInterview), 200);
    }

    public function show(InterviewManagerContract $interviewManager, Jobad $jobad)
    {
        return response(new InterviewCollection($interviewManager->getAll()), 200);
    }

}
