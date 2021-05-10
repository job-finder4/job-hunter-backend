<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $dates = [
        'start_date',
        'end_date'
    ];

    protected $guarded = [];

    public function jobad()
    {
        return $this->belongsTo(Jobad::class)->expired();
    }

}
