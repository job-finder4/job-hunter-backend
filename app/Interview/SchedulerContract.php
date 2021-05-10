<?php


namespace App\Interview;


use App\Exceptions\InterviewException;
use App\Models\Jobad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class SchedulerContract
{
    protected $jobad;
    protected $days;
    protected $duration;
    protected $break;
    protected $contact_info;
    private $interviews=[];

    public function __construct(Jobad $jobad)
    {
        $this->jobad = $jobad;
    }

    protected abstract function schedule():void;

    public function run(array $interviewSettings): array
    {
        $this->checkIfJobIsAbleToInterviewScheduling();

        $this->instantiateInterviewSetting($interviewSettings);

        $this->schedule();

        $this->checkIfInterviewsSufficeAllInterviewers();

        return $this->interviews;
    }

    private function checkIfInterviewsSufficeAllInterviewers()
    {
        if ($this->jobad->applications()->where('status',1)->count() > count($this->interviews))
            throw InterviewException::notEnoughInterviews();
        return true;
    }

    public function checkIfJobIsAbleToInterviewScheduling()
    {
        if($this->jobad->applications()->where('status',0)->exists())
            throw InterviewException::unEvaluatedApplicationsExists();

        if ($this->jobad->interviews()->exists())
            throw InterviewException::tooManyScheduling();

        return true;
    }

    private function validateInterviewSetting($interviewSettings)
    {
        return Validator::validate($interviewSettings, [
            'days' => ['required', 'array'],
            'duration' => ['required'],
            'break' => ['required'],
            'contact_info' => ['required']
        ]);
    }

    private function instantiateInterviewSetting($interviewSettings)
    {

        $data = $this->validateInterviewSetting($interviewSettings);

        $this->days = $data['days'];
        $this->duration = $data['duration'];
        $this->break = $data['break'];
        $this->contact_info = $data['contact_info'];
    }

    protected function pushInterview($start_date, $end_date)
    {
        $this->interviews[] = [
            'user_id' => null,
            'jobad_id' => $this->jobad->id,
            'start_date' => $start_date->copy(),
            'end_date' => $end_date,
            'contact_info' => $this->contact_info,
            'created_at' => now(),
        ];
    }
}
