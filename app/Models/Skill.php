<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Kalnoy\Nestedset\NodeTrait;
class Skill extends Model
{
    use HasFactory,NodeTrait;

    protected $guarded = [];

    public function jobads()
    {
        return $this->morphedByMany('App\Models\Jobad', 'skillable');
    }
}
