<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobadCollection;
use App\Models\Jobad;
use App\Http\Resources\Jobad as JobadResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
            'approved_at' => ''
        ]);

        $job = $company->jobads()->create($data);
        return response()->json(new JobadResource($job), 201);
    }

    public function index(){
        return response(new JobadCollection(Jobad::get()),200);
    }
}
