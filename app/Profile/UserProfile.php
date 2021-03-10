<?php


namespace App\Profile;


use Illuminate\Support\Arr;
use PhpParser\Node\Expr\Cast\Object_;

class UserProfile
{
    /*** @var string|null */
    public $location = '';

    /*** @var string|null */
    public $phone_number = '';

    /*** @var array */
    public $works_experience = [];

    /*** @var array */
    public $educations = [];

    /*** @var array */
    public $languages = [];


    public static function make($details)
    {
        $userProfile = new UserProfile();
        $userProfile->setLocation($details);
        $userProfile->addEducations($details);
        $userProfile->addWorksExperience($details);
        $userProfile->addLanguages($details);
        $userProfile->phone_number = isset($details['phone_number']) ? $details['phone_number'] : '';
        return $userProfile;
    }

    private static function indexOf(Object $item, array $array)
    {
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i]->id == $item->id)
                return $i;
        }
        return -1;
    }

    public function add($newDetails)
    {
        $this->addEducations($newDetails);
        $this->addWorksExperience($newDetails);
        $this->addLanguages($newDetails);
        return $this;
    }

    /**
     * @param object|null $location
     */
    public function setLocation($details): void
    {
        if (isset($details['location'])) {
            $this->location = $details['location']['city'] . ', ' . $details['location']['country'];
        }

    }

    /**
     * @param array $educations
     */
    public function addEducations(array $details): void
    {
        if (isset($details['educations'])) {
            $this->educations = array_merge(
                $this->educations,
                Education::makeMany($details['educations'])
            );
        }
    }

    /**
     * @param array $worksExperience
     */
    public function addWorksExperience(array $details): void
    {
        if (isset($details['works_experience'])) {
            $this->works_experience = array_merge(
                $this->works_experience,
                WorkExperience::makeMany($details['works_experience'])
            );
        }
    }

    public function addLanguages(array $details)
    {
        if (isset($details['languages'])) {
            if (is_array($details['languages'])) {
                $this->languages = array_merge($details['languages'], $this->languages);
            } else {
                $this->languages[] = $details['languages'];
            }
        }
    }

    public function update($details)
    {

        if (isset($details['phone_number']))
            $this->phone_number = $details->phone_number;

        $this->setLocation($details);

        $this->updateEducations($details);
        $this->updateWorksExperience($details);
        return $this;
    }


    public function equals(UserProfile $userProfile): bool
    {
        $tmp = $this->phone_number == $userProfile->phone_number &&
            $this->location == $userProfile->location &&
            count($this->educations) == count($userProfile->works_experience) &&
            count($this->works_experience) == count($userProfile->works_experience) &&
            count($this->languages) == count($userProfile->languages);
        if (!$tmp) return false;

        for ($i = 0; $i < count($this->educations); $i++) {
            if (!$this->educations[$i]->equals($userProfile->educations[$i]))
                return false;
        }
        for ($i = 0; $i < count($this->works_experience); $i++) {
            if (!$this->works_experience[$i]->equals($userProfile->works_experience[$i]))
                return false;
        }
        for ($i = 0; $i < count($this->languages); $i++) {
            if ($this->languages[$i] != $userProfile->languages[$i])
                return false;
        }
        return true;
    }

    public function updateEducations($details): void
    {
        if (!isset($details['educations']))
            return;
        foreach ($details['educations'] as $eduItem) {
            $index = self::indexOf($eduItem, $this->educations);
            $this->educations[$index]->update($eduItem);
        }
    }

    public  function updateWorksExperience($details)
    {
        if (!isset($details['works_experience']))
            return;
        foreach ($details['works_experience'] as $workItem) {
            $index = self::indexOf($workItem, $this->works_experience);
            $this->works_experience[$index]->update($workItem);
        }
    }
}

