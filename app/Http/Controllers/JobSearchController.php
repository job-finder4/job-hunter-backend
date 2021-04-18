<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobadCollection;
use App\Models\Jobad;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class JobSearchController extends Controller
{
    const EXACT_SKILL_POINTS = 14;
    const CHILD_SKILL_POINTS = 13;
    const PARENT_SKILL_POINTS = 12;
    const JOB_TITLE_POINTS = 6;
    const LOCATION_POINTS = 3;
    const SALARY_POINTS = 1;

    public function __invoke(Request $request)
    {
        $points = [];

        if ($request->has('skill')) {
            $skill = Skill::where('name', 'like', $request->skill)
                ->with('jobads', 'ancestors.jobads', 'descendants.jobads')
                ->firstOrFail();

            $skill->jobads->each(function ($job) use (& $points) {
                if (!Arr::has($points, $job->id))
                    $points[$job->id] = self::EXACT_SKILL_POINTS;
            });

            $skill->ancestors
                ->each(function ($ancestorSkill) use (& $points) {
                    $ancestorSkill->jobads->each(function ($job) use (& $points) {
                        if (!array_key_exists($job->id, $points))
                            $points[$job->id] = self::PARENT_SKILL_POINTS;
                    });
                });

            $skill->descendants
                ->each(function ($descendantsSkill) use (& $points) {
                    $descendantsSkill->jobads->each(function ($job) use (& $points) {
                        if (!array_key_exists($job->id, $points))
                            $points[$job->id] = self::CHILD_SKILL_POINTS;
                    });
                });
        }

        if ($request->has('job_title')) {
            Jobad::where('title', 'like', $request->job_title)
                ->get()->each(function ($job) use (& $points) {
                    if (!array_key_exists($job->id, $points))
                        $points[$job->id] = 0;
                    $points[$job->id] += self::JOB_TITLE_POINTS;
                });
        }


        if ($request->has('location')) {
            Jobad::where('location', $request->location)
                ->get()->each(function ($job) use (& $points) {
                    if (!array_key_exists($job->id, $points))
                        $points[$job->id] = 0;
                    $points[$job->id] += self::LOCATION_POINTS;
                });
        }

        if ($request->has('salary')) {
            Jobad::where('max_salary', '>=', $request->salary)
                ->get()->each(function ($job) use (& $points) {
                    if (!array_key_exists($job->id, $points))
                        $points[$job->id] = 0;
                    $points[$job->id] += self::SALARY_POINTS;
                });
        }

        $goalJobads = Jobad::whereIn('id', array_keys($points))->get()
            ->sortByDesc(function ($job) use ($points) {
                return $points[$job->id];
            })->values()->all();


        return response()->json(new JobadCollection($goalJobads), 200);
    }

}
