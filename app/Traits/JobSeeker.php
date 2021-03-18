<?php

namespace App\Traits;

use App\Models\Cv;
use App\Models\Profile;
use App\Models\Skill;
use App\Profile\UserProfile;

trait JobSeeker
{
    public function cvs()
    {
        return $this->hasMany(Cv::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function skills()
    {
        return $this->morphToMany(Skill::class,'skillable');
    }

    public function addProfileDetails($request)
    {
        if (!$this->profile()->exists()) {
            $storedProfile = Profile::makeNew($request);
        } else {
            $storedProfile = $this->profile->addDetails($request->details);
        }
        return $storedProfile;
    }
}
