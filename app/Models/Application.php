<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUserIdAttribute()
    {
        return $this->cv->user_id;
    }

    public function Jobad()
    {
        return $this->belongsTo(Jobad::class);
    }

    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }

}
