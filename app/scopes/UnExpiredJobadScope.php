<?php


namespace App\scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class UnExpiredJobadScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->whereDate('expiration_date', '>', now());
    }
}
