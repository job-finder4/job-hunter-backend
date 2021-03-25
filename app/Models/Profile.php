<?php

namespace App\Models;

use App\Profile\UserProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'details' => 'array'
    ];

    public static function makeNew($profile)
    {
        $details = UserProfile::make($profile['details']);

        $profile = auth()->user()->profile()->create([
            'details' => $details
        ]);
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


}
