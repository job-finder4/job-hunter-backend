<?php

namespace App\Traits;
use App\Models\Profile;
use App\Profile\UserProfile;

trait JobSeeker
{
    public function setProfile($details, $visibility)
    {
        $profile = new UserProfile($details);
        $this->profile()->create(['details' => $profile,'visibility' => $visibility]);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}
