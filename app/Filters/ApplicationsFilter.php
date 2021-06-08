<?php

namespace App\Filters;
use App\User;
use Illuminate\Http\Request;

class ApplicationsFilter extends QueryFilters
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    public function approved()
    {
        return $this->builder->where('status',1);
    }

     public function rejected()
    {
        return $this->builder->where('status',-1);
    }

     public function pending()
    {
        return $this->builder->where('status',0);
    }

}