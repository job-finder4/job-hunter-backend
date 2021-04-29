<?php


namespace App\Traits;


use App\Models\Skill;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait JobadSearch
{
    use FullTextSearch;

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
