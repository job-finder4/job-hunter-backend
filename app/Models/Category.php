<?php

namespace App\Models;

use App\Traits\FullTextSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, FullTextSearch;

    public $timestamps = false;

    protected $searchable = [
        'name'
    ];

    public function jobads(){
        return $this->hasMany(Jobad::class);
    }
}
