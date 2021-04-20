<?php


namespace App\Filters;
use App\User;
use Illuminate\Http\Request;

class JobadFilter extends QueryFilters
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
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

     public function expired()
    {
        return $this->builder->expired();
    }

     public function pending()
    {
        return $this->builder->Unapproved();
    }

    public function sort_age($type = null)
    {
        return $this->builder->orderBy('dob', (!$type || $type == 'asc') ? 'desc' : 'asc');
    }
}
