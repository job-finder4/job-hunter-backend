<?php


namespace App\scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ApprovedJobadScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        return $builder->whereNotNull('approved_at');
    }
}
