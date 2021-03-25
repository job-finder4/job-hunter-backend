<?php

namespace App\Http\Controllers;

use App\Exceptions\MyModelNotFoundException;
use App\Http\Resources\JobadCollection;
use App\Models\Jobad;
use App\Http\Resources\Jobad as JobadResource;
use App\Models\Skill;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class JobadController extends Controller
{
    public function store(Request $request)
    {
        $company = auth()->user();

        $data = $request->validate([
            'title' => '',
            'description' => '',
            'min_salary' => '',
            'max_salary' => '',
            'job_type' => '',
            'job_time' => '',
            'location' => '',
            'expiration_date' => '',
            'skills' => 'required',
            'approved_at' => ''
        ]);

        $skills = $data['skills'];

        $skillsIds = [];
        foreach ($skills as $skill) {
            Skill::findOrFail($skill['id']);
            $skillsIds[] = $skill['id'];
        }
        $data = Arr::except($data, ['skills']);

        $jobad = auth()->user()->jobads()->create($data);
        $jobad->skills()->attach($skillsIds);

        return response()->json(new JobadResource($jobad), 201);
    }

    public function index()
    {
        return response(new JobadCollection(Jobad::get()), 200);
    }
	 public function show(Jobad $jobad)
    {
        return response(new JobadResource($jobad), 200);
    }

    public function update(Request $request, Jobad $jobad)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => '',
            'min_salary' => '',
            'max_salary' => '',
            'job_type' => '',
            'job_time' => '',
            'location' => '',
            'skills' => 'required'
        ]);

        if (isset($data['skills'])) {
            $skills = $data['skills'];
            $skillsIds = [];

            foreach ($skills as $skill) {
                Skill::findOrFail($skill['id']);
                $skillsIds[] = $skill['id'];
            }

            $jobad->skills()->sync($skillsIds);
        }

        $data = Arr::except($data, ['skills']);
        $jobad->update($data);

//        if(!$jobad->isDirty()){
//            return response()->json(['message'=>'there is no change'], 204);
//        }
        return response(new JobadResource($jobad->refresh()), 200);
    }

}
