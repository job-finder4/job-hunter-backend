<?php


namespace App\Profile;


use Illuminate\Support\Arr;

class UserProfile
{
    /*** @var string|null */
    public $location;

    /*** @var string|null */
    public $phoneNumber;

    /*** @var array */
    public $worksExperience = [];

    /*** @var array */
    public $educations = [];

    /*** @var array */
    public $languages = [];

    public function __construct($details)
    {
        $this->setLocation($details->location);
        $this->setEducations($details->educations);
        $this->setWorksExperience($details->work_experience);
        $this->languages = $details->languages;
        $this->phoneNumber = $details->phoneNumber;
    }

    /**
     * @param object|null $location
     */
    public function setLocation($location): void
    {

        $this->location = $location->city.', '.$location->country;
    }

    /**
     * @param array $educations
     */
    public function setEducations(array $educations): void
    {
        $this->educations = Education::createMany($educations);
    }

    /**
     * @param array $worksExperience
     */
    public function setWorksExperience(array $worksExperience): void
    {
        $this->worksExperience = WorkExperience::createMany($worksExperience);
    }

}
