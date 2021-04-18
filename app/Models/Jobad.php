<?php

namespace App\Models;

use App\scopes\ApprovedJobadScope;
use App\scopes\UnExpiredJobadScope;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobad extends Model
{
    use HasFactory,Filterable;

    const FULL_TIME = 'full_time';
    const PART_TIME = 'part_time';
    const REMOTE = 'remote';
    const ON_SITE = 'on_site';

    protected $guarded = ['approved_at', 'user_id'];
    protected $dates = ['expiration_date', 'approved_at'];


    protected static function booted()
    {
        static::addGlobalScope(new ApprovedJobadScope);
        static::addGlobalScope(new UnExpiredJobadScope);
    }

    public function scopeUnapproved($query)
    {
        return $query->withoutGlobalScope(ApprovedJobadScope::class)
            ->where('approved_at', null);
    }

    public function scopeExpired(Builder $query)
    {
        return $query->withoutGlobalScope(UnExpiredJobadScope::class)
            ->where('expiration_date', '<', now());
    }

    public function scopeActiveAndInactive(Builder $query)
    {
        return $query->withoutGlobalScopes([UnExpiredJobadScope::class, ApprovedJobadScope::class]);
    }

    public function scopeInactive(Builder $query)
    {
        return $query->activeAndInactive()
            ->where(function (Builder $query) {
                $query->where('expiration_date', '<', now())
                    ->orWhere('approved_at', null);
            });

    }

    public function skills()
    {
        return $this->morphToMany('App\Models\Skill', 'skillable');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

}
