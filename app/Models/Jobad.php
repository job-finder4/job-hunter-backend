<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobad extends Model
{
    use HasFactory;

    const FULL_TIME = 'full_time';
    const PART_TIME = 'part_time';
    const REMOTE = 'remote';
    const ON_SITE = 'on_site';

    protected $guarded = [];
    protected $casts = ['salary' => 'array','expiration_date'=>'date'];

    public function skills()
    {
        return $this->morphToMany('App\Models\Skill', 'skillable');
    }
}
