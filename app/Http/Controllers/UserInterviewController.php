<?php

namespace App\Http\Controllers;

use App\Http\Resources\InterviewCollection;
use App\Interview\UserInterviews\UserInterviews;
use App\Interview\UserInterviews\UserInterviews as UserInterviewsAlias;
use Illuminate\Http\Request;

class UserInterviewController extends Controller
{
    public function index(UserInterviews $userInterviews)
    {
        return response(new InterviewCollection($userInterviews::getAll()),200);
    }
}
