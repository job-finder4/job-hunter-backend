<?php

namespace App\Traits;

use App\Models\Cv;
use App\Models\Profile;
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

    public function addProfileDetails($profile)
    {
        if (!$this->profile()->exists()) {
            $storedProfile = Profile::makeNew($profile);
        } else {
            $storedProfile = $this->profile->addDetails($profile['details']);
        }
        return $storedProfile;
    }
}
