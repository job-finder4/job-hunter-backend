<?php

namespace App\Models;

use App\scopes\ApprovedJobadScope;
use App\scopes\UnExpiredJobadScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobad extends Model
{
    use HasFactory;

    const FULL_TIME = 'full_time';
    const PART_TIME = 'part_time';
    const REMOTE = 'remote';
    const ON_SITE = 'on_site';

    protected $guarded = ['approved_at','user_id'];
    protected $dates = ['expiration_date'];

    protected static function booted()
    {
        static::addGlobalScope(new ApprovedJobadScope);
        static::addGlobalScope(new UnExpiredJobadScope);
    }
    public function scopeUnapproved($query)
    {
        return $query->withoutGlobalScope(ApprovedJobadScope::class);
    }
    public function scopeExpired($query)
    {
        return $query->withoutGlobalScope(UnExpiredJobadScope::class);
    }

    public function skills()
    {
        return $this->morphToMany('App\Models\Skill', 'skillable');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
