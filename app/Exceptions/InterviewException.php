<?php

namespace App\Exceptions;

use Exception;

class InterviewException extends Exception
{
    //
    public static function unauthorizedToReserve()
    {
        return new static('you are not permitted to reserve an interview for this job',403);
    }

    public static function interviewsMostGreater()
    {
        return new static('you must wide your intervals or add some from',400);
    }

    public static function tooManyReservations()
    {
        return new static('users are permitted to reserve only one interview per job',409);
    }

    public static function tooManyScheduling()
    {
        return new static('each job can only has one scheduled interviews',409);
    }

    public static function unEvaluatedApplicationsExists()
    {
        return new static('jobad has an applications is not evaluated yet',400);
    }

    public static function notEnoughInterviews()
    {
        return new static('can\'t schedule interviews fit all interviewers',400);
    }

    public function render($request)
    {
        return response()->json([
            'errors' => [
                'code' => $this->getCode(),
                'description' => $this->getMessage(),
            ]
        ],$this->getCode());
    }

}
