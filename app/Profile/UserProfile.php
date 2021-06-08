<?php


namespace App\Profile;

use App\Exceptions\MyModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

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
        self::validateUserProfile($details);
        $userProfile = new UserProfile();
        $userProfile->setLocation($details);
        $userProfile->addEducations($details);
        $userProfile->addWorksExperience($details);
        $userProfile->updateLanguages($details);
        $userProfile->phone_number = isset($details['phone_number']) ? $details['phone_number'] : '';
        return $userProfile;
    }

    //add new details to currently details
    public function add($newDetails)
    {
        self::validateUserProfile($newDetails);
        $this->addEducations($newDetails);
        $this->addWorksExperience($newDetails);
        return $this;
    }

    public function update($details)
    {
        self::validateUserProfile($details);
        if (isset($details['phone_number']))
            $this->phone_number = $details['phone_number'];

        $this->setLocation($details);
        $this->updateEducations($details);
        $this->updateWorksExperience($details);
        $this->updateLanguages($details);
        return $this;
    }

    public function delete($details)
    {
        self::validateUserProfile($details);
        $this->deleteEducations($details);
        $this->deleteWorksExperience($details);
        return $this;
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

    public function updateLanguages(array $details)
    {
        if (isset($details['languages'])) {
            $this->languages = $details['languages'];
        }
    }


    public function setLocation($details): void
    {
        if (isset($details['location'])) {
            $this->location = $details['location']['city'] . ', ' . $details['location']['country'];
        }

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

    public function updateWorksExperience($details)
    {
        if (!isset($details['works_experience']))
            return;
        foreach ($details['works_experience'] as $workItem) {
            $index = self::indexOf($workItem, $this->works_experience);
            $this->works_experience[$index]->update($workItem);
        }
    }

    private function deleteEducations($details)
    {
        if (!isset($details['educations']))
            return;

        foreach ($details['educations'] as $eduItem) {
            $index = self::indexOf($eduItem, $this->educations);
            array_splice($this->educations, $index, 1);
        }
    }

    private function deleteWorksExperience($details)
    {
        if (!isset($details['works_experience']))
            return;
        foreach ($details['works_experience'] as $workItem) {
            $index = self::indexOf($workItem, $this->works_experience);
            array_splice($this->works_experience, $index, 1);
        }
    }

    private static function indexOf(array $item, array $array)
    {
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i]->id == $item['id'])
                return $i;
        }
        throw new MyModelNotFoundException(class_basename(Arr::first($array)) . ' Not Found');
    }

    public static function validateUserProfile($attrs)
    {
        Validator::validate($attrs, [
            'location' => ['sometimes', 'required', 'array'],
            'phone_number' => ['sometimes', 'required'],
            'educations' => ['sometimes', 'required', 'array'],
            'works_experience' => ['sometimes', 'required', 'array'],
            'languages' => ['sometimes', 'required', 'array']
        ]);
    }

    //    in test mode only
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


}
