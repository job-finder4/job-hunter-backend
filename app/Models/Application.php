<?php

namespace App\Models;

use App\scopes\UnExpiredJobadScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;

class Application extends Model
{
    use HasFactory, Filterable;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUserIdAttribute()
    {
        return $this->cv['user_id'];
    }

    public function Jobad()
    {
        return $this->belongsTo(Jobad::class)->withoutGlobalScope(UnExpiredJobadScope::class);
    }

    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }

    public function scopeApproved()
    {
        return $this->where('status', 1);
    }

    public function scopeRejected()
    {
        return $this->where('status', -1);
    }
}
