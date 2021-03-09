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
        return response()->json(new JobadResource($job), 201);

    }

    public function index()
    {
        return response(new JobadCollection(Jobad::get()), 200);
    }


    public function update(Request $request, Jobad $jobad)
    {

        $data = $request->validate([
            'title' => '',
            'description' => '',
            'min_salary' => '',
            'max_salary' => '',
            'job_type' => '',
            'job_time' => '',
            'location' => '',
            'expiration_date' => '',
            'skills' => 'required'
        ]);

        if ($request->company_id != $jobad->company_id) {
            return response([], 404);
        }

        if ($request->has('skills')) {
            $skills = $data['skills'];
            $skillsIds = [];

            foreach ($skills as $skill) {
                Skill::findOrFail($skill['id']);
                $skillsIds[] = $skill['id'];
            }

            $jobad->skills()->sync($skillsIds);
        }

        $data = Arr::except($data, ['min_salary', 'max_salary', 'skills']);
        $jobad->update($data);

//        $title = $request->title;
//        $description = $request->description;
//        $min_salary = $request->min_salary;
//        $max_salary = $request->max_salary;
//        $job_type = $request->job_type;
//        $job_time = $request->job_time;
//        $location = $request->location;
//        $expiration_date = $request->expiration_date;
//        $skills = $request->skills;

//        $jobad->update([
//            'title' => $title,
//            'description' => $description,
//            'min_salary' => $min_salary,
//            'max_salary' => $max_salary,
//            'job_type' => $job_type,
//            'job_time' => $job_time,
//            'location' => $location,
//            'expiration_date' => $expiration_date,
//            'skills' => $skills,
//        ]);

        return response(new JobadResource($jobad->refresh()), 200);
    }

}
