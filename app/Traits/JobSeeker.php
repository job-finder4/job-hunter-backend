<?php

namespace App\Traits;

use App\Exceptions\FileSizeMismatchException;

use App\Exceptions\InvalidJobadApplicationException;
use App\Exceptions\ValidationErrorException;
use App\Models\Application;
use App\Models\Cv;
use App\Models\Jobad;
use App\Models\Profile;
use App\Models\Skill;
use App\Profile\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait JobSeeker
{
    public function applications()
    {
        return $this->hasManyThrough(Application::class,Cv::class);

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

    public function createCv($cvDetails)
    {
        $file = $cvDetails['file'];
        $title = $cvDetails['title'];

        if (($file->getSize() / (1024 * 1024)) > 4) {
            throw new FileSizeMismatchException();
        }

//        $filename = $file->getClientOriginalName();
        $destinationPath = storage_path() . '/app/cvs/';
//        $file->move($destinationPath, $filename);

        $uniqueName = '/cvs/' . $this->id . '/' ;
        $file->storeAs($uniqueName, $file->getClientOriginalName(), 'local');
//        Storage::disk('local')->put($uniqueName,  $file);
        $cv = $this->cvs()->create(['title' => $title, 'path' => $uniqueName.$file->getClientOriginalName()]);

        return $cv;
    }

}
