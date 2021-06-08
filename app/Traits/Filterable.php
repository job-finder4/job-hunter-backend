<?php

namespace App\Traits;
use App\Filters\QueryFilters;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }
}
