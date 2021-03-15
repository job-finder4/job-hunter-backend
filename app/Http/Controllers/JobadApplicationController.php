<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidJobadApplicationException;
use App\Exceptions\ValidationErrorException;
use App\Http\Resources\Application as ApplicationResource;
use App\Http\Resources\ApplicationCollection;
use App\Models\Application;
use App\Models\Jobad;
use Illuminate\Http\Request;


class JobadApplicationController extends Controller
{
    public function index(Jobad $jobad)
    {
        return response()->json(
            new ApplicationCollection($jobad->applications),
            200);
    }


    public function show(Jobad $jobad, Application $application)
    {
        return
//            $application->jobad_id != $jobad->id ? response()->json([], 404) :
            response()->json(new ApplicationResource($application), 200);
    }


    public function store(Jobad $jobad, Request $request)
    {
        $request->validate([
            'cv_id' => '',
        ]);

        if (!isset($request->cv_id) && !isset($request->cv_details['file'])) {
            throw new ValidationErrorException(json_encode([
                'cv' => 'cv id and cv file are missing'
            ]));
        }

        $cv_id = $request->cv_id;

        if (isset($request->cv_id)) {
            $cv = auth()->user()->cvs()->where('id', $cv_id)->firstOrFail();
        }

        if ($request->has('cv_details.file')) {
            $data = $request->validate([
                'cv_details.title' => 'required',
                'cv_details.file' => 'required'
            ]);
            $data = $data['cv_details'];
            $cv = auth()->user()->createCv($data);
            $cv_id = $cv->id;
        }

        if (!is_null(auth()->user()->applications()->where('jobad_id', $jobad->id)->first())) {
            throw  InvalidJobadApplicationException::alreadyExists();
        }

        $application = Application::create([
            'cv_id' => $cv_id,
            'jobad_id' => $jobad->id
        ]);

        return response()->json(new ApplicationResource($application), 201);
    }

}
