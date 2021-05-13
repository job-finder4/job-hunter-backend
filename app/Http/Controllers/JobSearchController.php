<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobadCollection;
use App\Models\Jobad;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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
        $searchVariables = [];

        //title
        $title = 'manager';

        $min_salary = 3000;
        $max_salary = 100000;
//        $location = 'Polynesia';
//        $skill = 'Dental';

        $skills = ["Spotters", "Carpenter Assembler and Repairer", "Video Editor"];

        $allColumns = 'id, user_id, category_id, title, description
                    ,min_salary, min_salary, job_type, job_time,
                    location, approved_at, expiration_date, created_at, updated_at';

//         $titleResults = Jobad::where('title', 'like', "%$title%")
//             ->addSelect(DB::raw('jobads.* , 6 as score'))
//             ->groupBy(
//                 DB::raw($allColumns)
//             );

        //max_salary
//         $max_salaryResults = Jobad::where('max_salary', '<=', $max_salary)
//            ->selectRaw('jobads.* ,1 as score')
//            ->groupBy(
//                DB::raw($allColumns)
//            );


        //location
        $locationMatchResults = null;
        if ($request->has('location')) {
            $location=$request->location;
            $locationMatchResults = Jobad::where('location', 'like', "%$location%")
                ->selectRaw('jobads.* , 3 as score')
                ->groupBy(
                    DB::raw($allColumns)
                );
        }

        //category
        $categoryMatchResults = null;
        if ($request->has('category')) {
            $categoryId = $request->category;
            $categoryMatchResults = Jobad::where('category_id', $categoryId)
                ->selectRaw('jobads.* , 6 as score')
                ->groupBy(
                    DB::raw($allColumns)
                );
        }


        //skills
        $global_skills_jobads_match = null;

        if ($request->has('skills')) {
            $skills = json_decode($request->skills);
            foreach ($skills as $singleSkill) {
                $skill_jobads = null;

                $skillWithJobads = Skill::where('name', $singleSkill)
                    ->with('jobads', 'ancestors.jobads', 'descendants.jobads')
                    ->firstOrFail();
                if (!$skillWithJobads) {
                    continue;
                }

                $skill_jobads = $skillWithJobads->jobads()
                    ->selectRaw('jobads.* , 14 as score')
                    ->groupBy(
                        DB::raw($allColumns)
                    );

                $skillWithJobads->descendants
                    ->each(function ($descendantsSkill) use (& $skill_jobads, $allColumns) {
                        if ($descendantsSkill->jobads) {
                            $tmp = $descendantsSkill->jobads()->selectRaw('jobads.* , 13 as score')
                                ->groupBy(
                                    DB::raw($allColumns)
                                );
                            if (!$skill_jobads) {
                                $skill_jobads = $tmp;
                            } else {
                                $skill_jobads->union($tmp);
                            }
                        }
                    });

                $skillWithJobads->ancestors
                    ->each(function ($ancestorSkill) use (& $skill_jobads, $allColumns) {
                        if ($ancestorSkill->jobads) {
                            $tmp = $ancestorSkill->jobads()->selectRaw('jobads.* , 12 as score')
                                ->groupBy(
                                    DB::raw($allColumns)
                                );
                            if (!$skill_jobads) {
                                $skill_jobads = $tmp;
                            } else {
                                $skill_jobads->union($tmp);
                            }
                        }
                    });

                if ($global_skills_jobads_match) {
                    $global_skills_jobads_match = $global_skills_jobads_match->unionAll($skill_jobads);
                } else {
                    $global_skills_jobads_match = $skill_jobads;
                }
            }
        }

        // $matchedJobs = $titleResults->unionAll($max_salaryResults)->unionAll($exact_skill_match);
        // $matchedJobs = $exact_skill_match->unionAll($parent_skills_match);
//        $matchedJobs = $skills_match->unionAll($categoryMatchResults)->unionAll($locationMatchResults);

        $searchVariables[] = $categoryMatchResults;
        $searchVariables[] = $locationMatchResults;
        $searchVariables[] = $global_skills_jobads_match;

        $matchedJobs = null;
        foreach ($searchVariables as $searchVariable) {
            if ($searchVariable) {
                if ($matchedJobs) {
                    $matchedJobs = $matchedJobs->unionAll($searchVariable);
                } else {
                    $matchedJobs = $searchVariable;
                }
            }
        }

        $res = DB::query()->fromSub($matchedJobs, 'subquery')
            ->groupBy(
                DB::raw($allColumns)
            )
            ->selectRaw('sum(score) AS all_score')
            ->orderByDesc('all_score')
            ->addSelect(
                DB::raw($allColumns)
            );


        return $res->get();

//--------------------------------------------------------------------------

//        if ($request->has('skill')) {
//            $skill = Skill::where('name', 'like', $request->skill)
//                ->with('jobads', 'ancestors.jobads', 'descendants.jobads')
//                ->firstOrFail();
//
//            $skill->jobads->each(function ($job) use (& $points) {
//                if (!Arr::has($points, $job->id))
//                    $points[$job->id] = self::EXACT_SKILL_POINTS;
//            });
//
//            $skill->ancestors
//                ->each(function ($ancestorSkill) use (& $points) {
//                    $ancestorSkill->jobads->each(function ($job) use (& $points) {
//                        if (!array_key_exists($job->id, $points))
//                            $points[$job->id] = self::PARENT_SKILL_POINTS;
//                    });
//                });
//
//            $skill->descendants
//                ->each(function ($descendantsSkill) use (& $points) {
//                    $descendantsSkill->jobads->each(function ($job) use (& $points) {
//                        if (!array_key_exists($job->id, $points))
//                            $points[$job->id] = self::CHILD_SKILL_POINTS;
//                    });
//                });
//        }

//        if ($request->has('job_title')) {
//            Jobad::where('title', 'like', $request->job_title)
//                ->get()->each(function ($job) use (& $points) {
//                    if (!array_key_exists($job->id, $points))
//                        $points[$job->id] = 0;
//                    $points[$job->id] += self::JOB_TITLE_POINTS;
//                });
//        }


//        if ($request->has('location')) {
//            Jobad::where('location', $request->location)
//                ->get()->each(function ($job) use (& $points) {
//                    if (!array_key_exists($job->id, $points))
//                        $points[$job->id] = 0;
//                    $points[$job->id] += self::LOCATION_POINTS;
//                });
//        }

//        if ($request->has('salary')) {
//            Jobad::where('max_salary', '>=', $request->salary)
//                ->get()->each(function ($job) use (& $points) {
//                    if (!array_key_exists($job->id, $points))
//                        $points[$job->id] = 0;
//                    $points[$job->id] += self::SALARY_POINTS;
//                });
//        }

//        $goalJobads = Jobad::whereIn('id', array_keys($points))->get()
//            ->sortByDesc(function ($job) use ($points) {
//                return $points[$job->id];
//            })->values()->all();


//        return response()->json(new JobadCollection($goalJobads), 200);
    }

}
