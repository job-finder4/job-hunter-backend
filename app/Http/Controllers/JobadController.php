<?php

namespace App\Http\Controllers;

use App\Http\Resources\Jobad as JobadResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
            'expiration_date' => ''
        ]);

        $salary = ['min_salary' => $data['min_salary'], 'max_salary' => $data['max_salary']];
        $data['salary'] = $salary;
        $data = Arr::except($data, ['min_salary', 'max_salary']);

        $job = auth()->user()->jobads()->create($data);
        return new JobadResource($job);

//        return response()->json([], 201);
    }
}
