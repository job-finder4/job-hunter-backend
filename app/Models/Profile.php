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

    public static function makeNew(Request $request)
    {
        $request->validate([
            'details' => 'required',
            'visible' => '',
            'skills' => ''
        ]);
        $details = UserProfile::make($request->details);

        $profile = auth()->user()->profile()->create([
            'details' => $details
        ]);

        auth()->user()->skills()->sync($request->skills);

        return $profile;
    }

    public function addDetails($newDetails)
    {
        $this->details = $this->details->add($newDetails);
        $this->save();
        return $this;
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
