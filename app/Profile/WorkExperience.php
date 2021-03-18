<?php


namespace App\Profile;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use phpseclib\Math\BigInteger;

class  WorkExperience{
    /*** @var integer */
    public $id;

    /*** @var string */
    public $job_title;

    /*** @var string */
    public $company_name;

    /*** @var Date */
    public $start_date;

    /*** @var Date */
    public $end_date;

    /*** @var string */
    public $industry;

    /*** @var string */
    public $job_category;

    /*** @var string */
    public $job_subcategory;

    /*** @var string */
    public $job_description;

    public function __construct($details)
    {
        $this->id = $details['id'];
        $this->job_title = $details['job_title'];
        $this->company_name = $details['company_name'];
        $this->start_date = $details['start_date'];
        $this->end_date = $details['end_date'];
        $this->industry = $details['industry'];
        $this->job_category = $details['job_category'];
        $this->job_subcategory = $details['job_subcategory'];
        $this->job_description = $details['job_description'];
    }

    /**
     * @param array $edusAttrs
     * @return array|array[]
     */
    public static function toArrayOfArrays(array $workAttrs): array
    {
        if (!is_array(Arr::first($workAttrs))) {
            $workAttrs = [$workAttrs];
        }
        return $workAttrs;
    }

    public function availableYears()
    {
        return range(now()->year - 20, now()->year);
    }

    public static function makeMany(array $worksAttrs): array
    {
        $worksAttrs = self::toArrayOfArrays($worksAttrs);
        $worksExperience = [];
        foreach ($worksAttrs as $workAttrs) {
            $worksExperience[] = self::make($workAttrs);
        }
        return $worksExperience;
    }

    public static function make($attrs): object
    {
        self::validateWorkExperience($attrs);
        $id = Str::uuid();
        $work = new static(array_merge($attrs, ['id' => $id]));
        return $work;
    }

    public function equals(WorkExperience $workExp): bool
    {
        return $this->id == $workExp->id &&
            $this->job_title == $workExp->job_title &&
            $this->company_name == $workExp->company_name &&
            $this->start_date == $workExp->start_date &&
            $this->end_date == $workExp->end_date &&
            $this->industry == $workExp->industry &&
            $this->job_category == $workExp->job_category &&
            $this->job_subcategory == $workExp->job_subcategory &&
            $this->job_description == $workExp->job_description;
    }

    public function update(Object $workAttr)
    {
        foreach ($workAttr as $key=>$attr)
        {
            if ($key == 'id')continue;
            $this->$key = $attr;
        }

    }

    public static function validateWorkExperience($attrs)
    {
        Validator::validate($attrs, [
            'job_title' => ['required','string','max:20'],
            'company_name' => ['required','string','max:20'],
            'start_date' => ['required','date'],
            'end_date' => ['sometimes','date','after:start_date'],
            'industry' => [],
            'job_category' => [],
            'job_subcategory' => [],
            'job_description' => []
        ]);
    }

}
