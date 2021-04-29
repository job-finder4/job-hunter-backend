<?php

namespace App\Http\Controllers;

use App\Events\JobadEvaluated;
use App\Exceptions\MyModelNotFoundException;
use App\Filters\JobadFilter;
use App\Http\Resources\CvCollection;
use App\Http\Resources\JobadCollection;
use App\Models\Jobad;
use App\Http\Resources\Jobad as JobadResource;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;


class JobadController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:create,App\Models\Jobad')->only('store');
        $this->middleware('can:update,jobad')->only('update');
        $this->middleware('can:approve,unapprovedJobad')->only('approve');
        $this->middleware('can:viewCompanyJobads,App\Models\Jobad')->only('getCompanyJobads');
    }

    //-----------------daniel modification-------------
    public function index(Request $request, JobadFilter $filters)
    {
        $resultSet = Jobad::filter($filters);

        if ($request->has('search')) {
            $resultSet = $resultSet->get()
                ->sortByDesc(function ($job) {
                    return $job->j_score + $job->s_score + $job->c_score;
                })
                ->values()
                ->forPage(request()->input('page', 0), 5);
        } else {
            $resultSet = $resultSet->orderByDesc('created_at')->paginate(5);
        }
        return response(new JobadCollection($resultSet), 200);
    }
    //----------------------------------------

    public function getCompanyJobads(Request $request,JobadFilter $filters)
    {
//        $allJobads = null;
//        if (!$request->has('filter')) {
//            $allJobads = auth()->user()->jobads()->activeAndInactive();
//        }
//
//        $filter = $request->filter;
//
//        if ($filter == 'active') {
//            $allJobads = auth()->user()->jobads();
//        }
//        if ($filter == 'expired') {
//            $allJobads = auth()->user()->jobads()->expired();
//        }
//        if ($filter == 'pending') {
//            $allJobads = auth()->user()->jobads()->Unapproved();
//        }
        $resultSet=auth()->user()->jobads()->filter($filters);
        return response(new JobadCollection($resultSet->paginate(5)), 200);
    }

    public function approve(Jobad $jobad)
    {
        $jobad->approved_at = now();
        $jobad->saveOrFail();
        event(new JobadEvaluated($jobad));
        return response(new JobadResource($jobad), 200);
    }

    public function store(Request $request)
    {
        $company = auth()->user();

        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'min_salary' => 'required',
            'max_salary' => 'required',
            'job_type' => 'required',
            'job_time' => 'required',
            'location' => 'required',
            'expiration_date' => 'required',
            'skills' => 'required',
            'category'=>'',
            'approved_at' => ''
        ]);

        $skills = $data['skills'];
        $skillsIds = [];
        foreach ($skills as $skillId) {
            Skill::findOrFail($skillId);
            $skillsIds[] = $skillId;
        }
        $data = Arr::except($data, ['skills']);

        $jobad = auth()->user()->jobads()->create($data);
        $jobad->skills()->attach($skillsIds);

        return response()->json(new JobadResource($jobad), 201);
    }

    public function show(Jobad $jobad)
    {
        return response(new JobadResource($jobad), 200);
    }

    public function update(Request $request, Jobad $jobad)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'min_salary' => 'required',
            'max_salary' => 'required',
            'job_type' => 'required',
            'job_time' => 'required',
            'location' => 'required',
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

        return response(new JobadResource($jobad->refresh()), 200);
    }

    public function getJobsForAdmin(Request $request,JobadFilter $filters)
    {
        $resultSet=Jobad::filter($filters);

           return response(new JobadCollection($resultSet->paginate(5)), 200);
    }

}
