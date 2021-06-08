<?php

namespace App\Models;

use App\Profile\UserProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\This;


class Profile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'details' => 'array'
    ];

    public static function createNew($request)
    {
        $request->validate([
            'details' => 'required|array',
            'visible' => '',
            'skills' => ''
        ]);
        $details = UserProfile::make($request->details);

        $profile = $request->user()->profile()->create([
            'details' => $details,
            'visible' => $request->visible
        ]);

        auth()->user()->skills()->sync($request->skills);

        return $profile;
    }

    public function getDetailsAttribute()
    {
        return unserialize($this->attributes['details']);
    }

    public function setDetailsAttribute($value)
    {
        $this->attributes['details'] = serialize($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
