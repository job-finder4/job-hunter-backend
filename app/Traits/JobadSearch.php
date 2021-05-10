<?php


namespace App\Traits;


use App\Models\Category;
use App\Models\Skill;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

trait JobadSearch
{
    use FullTextSearch;

    public function scopeSearchJob($query, $search)
    {
        $search = json_decode($search);

        foreach ($search as $key => $value) {
            $key = ucfirst($key);
            $query = $this->{"searchBy$key"}($query, $value);
        }
        return $query;
    }


    public function searchByLocation($query, string $location)
    {
        if (empty($location))
            return $query;

        return $query->where('location', 'like', "%$location%");
    }

    public function searchByTerm($query, array $term)
    {
        if (empty($term))
            return $query;

        $termAsText = implode(' ',$term);

        $allColumns = 'id, user_id, category_id, title, description
                    , min_salary, min_salary, job_type, job_time,
                    location, approved_at, expiration_date, created_at, updated_at';

        $jobsByCategoryName = $this->matchesByCategoryName($query, $termAsText);

        $jobsBySkillName = $this->matchesBySkillName($query, $termAsText);

        $jobsByJobTitle = $this->matchesByJobTitle($query, $termAsText);

        $matchedJobs = $jobsByJobTitle->unionAll($jobsByCategoryName)->unionAll($jobsBySkillName);

        return
            DB::query()->fromSub($matchedJobs, 'subquery')
                ->groupBy(
                    DB::raw($allColumns)
                )
                ->selectRaw('sum(score) AS all_score')
                ->orderByDesc('all_score')
                ->addSelect(
                    DB::raw($allColumns)
                );
    }

    /**
     * @param String $term
     * @return mixed
     */
    public function matchesByJobTitle($query, string $term)
    {
        return
            $query->search($term)->addSelect('jobads.*');
    }

    /**
     * @param String $term
     * @return mixed
     */
    public function matchesByCategoryName($query, string $term)
    {
        return
            Category::query()->search($term)
                ->rightJoinSub($query, 'jobads', function ($join) {
                    $join->on('categories.id', '=', 'jobads.category_id');
                })
                ->addSelect('jobads.*');
    }

    /**
     * @param String $term
     * @return mixed
     */
    public function matchesBySkillName($query, string $term)
    {
        return
            Skill::query()->search($term)
                ->leftJoin('skillables', function ($join) {
                    $join->on('skills.id', '=', 'skillables.skill_id')
                        ->where('skillable_type', 'App\Models\Jobad');
                })
                ->rightJoinSub($query, 'jobads', function ($join) {
                    $join->on('skillables.skillable_id', '=', 'jobads.id');
                })
                ->addSelect('jobads.*');
    }

}
