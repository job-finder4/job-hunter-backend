<?php


namespace App\Interview\UserInterviews;


use App\Models\Jobad;

class CompanyInterviews implements UserInterviews
{

    public static function getAll()
    {
        return auth()->user()->expiredJobads()->with('interviews')->get()->pluck('interviews')->flatten();
    }
}
