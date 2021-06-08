<?php


namespace App\Filters;

use App\Models\Jobad;
use App\Models\User;
use Illuminate\Http\Request;

class JobadFilter extends QueryFilters
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    public function search($searchParams)
    {
        $this->builder->searchJob($searchParams);
    }


    public function advancedSearch($searchParams)
    {
        $this->builder->advancedSearchJob($searchParams);
    }

    public function category($term)
    {
        return $this->builder->whereHas('category', function ($query) use ($term) {
            $query->where('id', $term);
        });
    }

    public function location($term)
    {
        return $this->builder->where('location', 'LIKE', "%$term%");
    }

    public function job_status($status)
    {
        if ($status == 'pending') {
            return $this->builder->pending();
        }
        if ($status == 'expired') {
            return $this->builder->expired();
        }
        if ($status == 'refused') {
            return $this->builder->refused();
        }
        return null;
    }

    public function job_time($time)
    {
        return $this->builder->where('job_time', $time);
    }

    public function job_type($type)
    {
        return $this->builder->where('job_type', $type);
    }

    public function min_salary($salary)
    {
        return $this->builder->where('min_salary', '>=', $salary);
    }

    public function max_salary($salary)
    {
        return $this->builder->where('max_salary', '<=', $salary);
    }
    //-----------------------------new
    public function job_times($times)
    {
        $timess=json_decode($times,true);
        return $this->builder->whereIn('job_time', $timess);
    }
    public function job_types($types)
    {
        $typess=json_decode($types,true);
        return $this->builder->whereIn('job_type', $typess);
    }
    public function locations($terms)
    {
        $termss=json_decode($terms,true);
        return $this->builder->whereIn('location',$termss);
    }
//---------------------------------

}
