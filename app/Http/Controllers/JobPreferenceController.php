<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobPreference as JobPreferenceResource;
use App\Models\Jobad;
use App\Models\JobPreference;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class JobPreferenceController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'job_title' => ['required'],
            'job_category' => ['required'],
            'salary' => ['required'],
            'location' => ['required'],
            'work_type' => ['required', Rule::in([Jobad::FULL_TIME, Jobad::PART_TIME])],
        ]);

        throw_if($request->user()->jobPreference()->exists(),
            ConflictHttpException::class,
            'you have job preference already... you can update it if you want but you cant create a new one'
        );

        $jobPreference = $request->user()->jobPreference()->create($data);

        return response()->json(new JobPreferenceResource($jobPreference),201);
    }

    public function update(Request $request)
    {

        $data = $request->validate([
            'job_title' => ['sometimes', 'required'],
            'job_category' => ['sometimes', 'required'],
            'salary' => ['sometimes', 'required'],
            'location' => ['sometimes', 'required'],
            'work_type' => ['sometimes', 'required', Rule::in([Jobad::FULL_TIME, Jobad::PART_TIME])],
        ]);
        $jobPreference = $request->user()->jobPreference()->firstOrFail();
        $jobPreference->update($data);

        return response()->json(new JobPreferenceResource($jobPreference->fresh()),200);
    }

    public function destroy(Request $request)
    {
        $request->user()->jobPreference()->delete();
        return response()->json([], 200);
    }
}
