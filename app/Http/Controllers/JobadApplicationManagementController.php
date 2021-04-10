<?php

namespace App\Http\Controllers;


use App\Events\ApplicationEvaluated;
use App\Http\Resources\Application as ApplicationResource;
use App\Models\Application;
use App\Models\Jobad;
use Illuminate\Http\Request;

class JobadApplicationManagementController extends Controller
{
    public function evaluate(Jobad $jobad, Application $application,Request $request)
    {
        $application->update([
            'status' => $request->status
        ]);
        //just for frontend to work
        //
        event(new ApplicationEvaluated($application));
        return response()->json(new ApplicationResource($application),200);
    }

}
