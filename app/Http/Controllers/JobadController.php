<?php

namespace App\Http\Controllers;

use App\Exceptions\MyModelNotFoundException;
use App\Http\Resources\Jobad;
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
            'skills' => 'required'
        ]);

        $salary = ['min_salary' => $data['min_salary'], 'max_salary' => $data['max_salary']];
        $data['salary'] = $salary;

        $skills = $data['skills'];

        $skillsIds = [];
        foreach ($skills as $skill) {
            Skill::findOrFail($skill['id']);
            $skillsIds[] = $skill['id'];
        }

        $data = Arr::except($data, ['min_salary', 'max_salary', 'skills']);

        $jobad = auth()->user()->jobads()->create($data);
        $jobad->skills()->attach($skillsIds);

        return new JobadResource($jobad);
    }

}
