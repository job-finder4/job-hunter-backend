<?php


namespace App\Traits;


use App\Models\Jobad;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait JobadSearch
{
    use FullTextSearch;

    public function scopeAdvancedSearchJob(Builder $query, $searchParams)
    {
        $searchParams = json_decode($searchParams,true);

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
                    ,min_salary, max_salary, job_type, job_time,
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
        if (isset($searchParams['location'])) {
            $location = $searchParams['location'];
            $locationMatchResults = Jobad::where('location', 'like', "%$location%")
                ->selectRaw('jobads.* , 3 as score')
                ->groupBy(
                    DB::raw($allColumns)
                );
        }

        //category
        $categoryMatchResults = null;
        if (isset($searchParams['category'])) {
            $categoryId = $searchParams['category'];
            $categoryMatchResults = Jobad::where('category_id', $categoryId)
                ->selectRaw('jobads.* , 6 as score')
                ->groupBy(
                    DB::raw($allColumns)
                );
        }


        //skills
        $global_skills_jobads_match = null;

        if (isset($searchParams['skills'])) {
            $skills = json_decode($searchParams['skills']);
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

        return $query->fromSub($matchedJobs, 'subquery')
            ->groupBy(
                DB::raw($allColumns)
            )
            ->selectRaw('sum(score) AS all_score')
            ->orderByDesc('all_score')
            ->addSelect(
                DB::raw($allColumns)
            );

//dd($res->get());
        return $res;
    }
//------------------------------------------------------------------------
    public function scopeSearchJob(Builder $query, $search)
    {
        $search = json_decode($search);
        foreach ($search as $key => $value) {
            $key = ucfirst($key);
            $query->{"searchBy$key"}($value);
        }
        return $query;
    }


    public function scopeSearchByLocation(Builder $query, array $location)
    {
        if (empty($location))
            return $query;
        return $query->whereIn('location', $location);

    }

    public function scopeSearchByTerm(Builder $query, array $term)
    {
        if (empty($term))
            return $query;
//        $columns = implode(' ', $this->searchable);

        $searchableTerm = $this->fullTextWildcards($term);

        $query
            ->selectRaw('jobads.*')
            ->selectRaw('categories.name AS category_name,skills.name AS skill_name')
            ->selectRaw(
                "MATCH (jobads.title) AGAINST (? IN BOOLEAN MODE) AS j_score",
                [$searchableTerm]
            )
            ->selectRaw(
                "MATCH (categories.name) AGAINST (? IN BOOLEAN MODE) AS c_score",
                [$searchableTerm]
            )
            ->selectRaw(
                "sum(MATCH (skills.name) AGAINST (? IN BOOLEAN MODE)) AS s_score",
                [$searchableTerm]
            )
            ->leftJoin('categories', 'jobads.category_id', '=', 'categories.id')
            ->leftJoin('skillables', function ($join) {
                $join->on('jobads.id', '=', 'skillables.skillable_id')
                    ->where('skillable_type', 'App\Models\Jobad');
            })
            ->leftJoin('skills', 'skillables.skill_id', '=', 'skills.id')
            ->WhereRaw(
                "MATCH (jobads.title) AGAINST (? IN BOOLEAN MODE)",
                $searchableTerm
            )
            ->orWhereRaw(
                "MATCH (categories.name) AGAINST (? IN BOOLEAN MODE)",
                $searchableTerm
            )
            ->orWhereRaw(
                "MATCH (skills.name) AGAINST (? IN BOOLEAN MODE)",
                $searchableTerm
            )
            ->groupByRaw(
                'jobads.id,jobads.title,jobads.description,
                    jobads.min_salary,jobads.max_salary,jobads.job_type,jobads.job_time,
                    jobads.location,jobads.approved_at,jobads.expiration_date,jobads.created_at,j_score,c_score'
            );

        return $query;
    }

}
