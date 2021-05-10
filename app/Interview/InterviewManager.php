<?php


namespace App\Interview;


use App\Exceptions\InterviewException;
use App\Exceptions\MyModelNotFoundException;
use App\Models\Interview;
use App\Models\Jobad;
use App\Models\User;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class InterviewManager implements InterviewManagerContract
{
    protected $jobad;
    protected $scheduler;

    public function __construct(SchedulerContract $scheduler, Jobad $jobad)
    {
        $this->jobad = $jobad;
        $this->scheduler = $scheduler;
    }

    public function schedule(array $interviewSettings): void
    {
        $interviews = $this->scheduler->run($interviewSettings);
        Interview::insert($interviews);
    }

    public function reserve(Interview $interview, User $user): Interview
    {
        $this->checkIfReservationTerminate();
        $this->validateUser($user);

        $interview->update([
            'user_id' => $user->id
        ]);

        return $interview->fresh();
    }

    public function getAll()
    {
        $this->checkIfUserCanShowInterviewDashboard(auth()->user());
        $interviews = $this->jobad->interviews()->get();
        if ($interviews == [])
            throw new MyModelNotFoundException('job hasn\'t any interview yet');

        return $interviews;
    }

    private function checkUserApplicationStatus(User $user)
    {
        if (!$user->applications()->where('jobad_id', $this->jobad->id)->where('status', 1)->exists())
            throw InterviewException::unauthorizedToReserve();
    }

    private function checkIfReservationTerminate()
    {
        return true;
    }

    private function checkIfUserHasReservation(User $user)
    {
        if ($this->jobad->interviews()->where('user_id', $user->id)->exists())
            throw  InterviewException::tooManyReservations();
    }

    private function validateUser(User $user)
    {
        $this->checkIfUserHasReservation($user);
        $this->checkUserApplicationStatus($user);
    }

    public function checkIfUserCanShowInterviewDashboard(User $user)
    {
        if ($user->hasAnyRole(['company', 'admin']))
            return true;
        $this->checkUserApplicationStatus($user);
    }
}
