<?php


namespace App\Interview\UserInterviews;


class JobSeekerInterviews implements UserInterviews
{

    public static function getAll()
    {
        return auth()->user()->interviews()->get();
    }
}
