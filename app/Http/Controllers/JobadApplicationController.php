<?php

namespace App\Http\Controllers;

use App\Http\Resources\Application as ApplicationResource;
use App\Models\Application;
use App\Models\Jobad;
use http\Env\Response;
use Illuminate\Http\Request;

class JobadApplicationController extends Controller
{
    public function store(Jobad $jobad, Request $request)
    {
        $application = auth()->user()->applications()->create([
            'jobad_id' => $jobad->id,
        ]);

        return response()->json(new ApplicationResource($application), 201);
    }

    public function show(Jobad $jobad, Application $application)
    {
        return
//            $application->jobad_id != $jobad->id ? response()->json([], 404) :
            response()->json(new ApplicationResource($application), 200);
    }

}
