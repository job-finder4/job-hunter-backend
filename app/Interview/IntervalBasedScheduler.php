<?php


namespace App\Interview;


use App\Exceptions\InterviewException;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\This;

class IntervalBasedScheduler extends SchedulerContract
{
    public function schedule():void
    {
        $days = $this->days;
        $duration = $this->duration;
        $break = $this->break;

        $start_date = Carbon::parse("{$days[0]['date']} {$days[0]['start_time']}");
        $end_date = Carbon::parse("{$days[0]['date']} {$days[0]['end_time']}");

        while (!empty($days)) {
            $interview_end = $start_date->copy()->addMinutes($duration);

            if ($interview_end->greaterThan($end_date)) {
                array_shift($days);
                if (!empty($days)) {
                    $start_date = Carbon::parse("{$days[0]['date']} {$days[0]['start_time']}");
                    $end_date = Carbon::parse("{$days[0]['date']} {$days[0]['end_time']}");
                }
                continue;
            }

            $this->pushInterview($start_date->copy(),$interview_end);

            $start_date->addMinutes($duration + $break);
        }

    }

}
