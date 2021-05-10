<?php

namespace App\Traits;

use App\Exceptions\FileSizeMismatchException;

use App\Exceptions\InvalidJobadApplicationException;
use App\Exceptions\ValidationErrorException;
use App\Models\Application;
use App\Models\Cv;
use App\Models\Interview;
use App\Models\Jobad;
use App\Models\JobPreference;
use App\Models\Profile;
use App\Models\Skill;
use App\Profile\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait JobSeeker
{
    public function applications()
    {
        return $this->hasManyThrough(Application::class, Cv::class);
    }

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
        return $this->morphToMany(Skill::class, 'skillable');
    }

    public function jobPreference(){
        return $this->hasOne(JobPreference::class);
    }

    public function addProfileDetails($request)
    {
        if (!$this->profile()->exists()) {
            $storedProfile = Profile::createNew($request);
        } else {
            $storedProfile = $this->profile;
            $details = $storedProfile->details->add($request->details);
            Profile::where('user_id', $request->user()->id)->update(['details' => serialize($details)]);
            $storedProfile = $storedProfile->fresh();
        }

        return $storedProfile;
    }

    public function deleteProfileDetails($request)
    {
        $profile = $this->profile()->firstOrFail();
        $details = $profile->details->delete($request->details);
        Profile::where('user_id', $this->id)->update(['details' => serialize($details)]);
        return $profile->fresh();
    }

    public function createCv($cvDetails)
    {
        $file = $cvDetails['file'];
        $title = $cvDetails['title'];

        if (($file->getSize() / (1024 * 1024)) > 4) {
            throw new FileSizeMismatchException();
        }

        $uniqueName = '/cvs/' . $this->id . '/';
        $file->storeAs($uniqueName, $file->getClientOriginalName(), 'local');
        $cv = $this->cvs()->create(['title' => $title, 'path' => $uniqueName . $file->getClientOriginalName()]);

        return $cv;
    }

    public function interviews(){
        return $this->hasMany(Interview::class);
    }

}
