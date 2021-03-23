<?php


namespace App\Profile;

use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\This;
use phpseclib\Math\BigInteger;

class Education
{
    /**
     * @var BigInteger
     */
    public $id;


    /**
     * @var Date
     */
    public $graduation_year;

    /**
     * @var string
     */
    public $institution;

    /**
     * @var string
     */
    public $study_field;

    /**
     * @var string
     */
    public $degree;


    public function __construct($details)
    {
        $this->id = $details['id'];
        $this->graduation_year = $details['graduation_year'];
        $this->institution = $details['institution'];
        $this->study_field = $details['study_field'];
        $this->degree = $details['degree'];
    }

    /**
     * @param array $edusAttrs
     * @return array|array[]
     */
    public static function toArrayOfArrays(array $edusAttrs): array
    {
        if (!is_array(Arr::first($edusAttrs))) {
            $edusAttrs = [$edusAttrs];
        }
        return $edusAttrs;
    }

    public function availableYears()
    {
        return range(now()->year - 20, now()->year);
    }

    public static function makeMany(array $edusAttrs): array
    {
        $edusAttrs = self::toArrayOfArrays($edusAttrs);
        $edus = [];
        foreach ($edusAttrs as $eduAttrs) {
            $edus[] = self::make($eduAttrs);
        }
        return $edus;
    }

    public static function make($attrs): object
    {
        self::validateEdu($attrs);
        $id = Str::uuid();
        $edu = new static(array_merge($attrs, ['id' => $id]));
        return $edu;
    }

    public function update(Array $eduAttr)
    {
        foreach ($eduAttr as $key=>$attr)
        {
            if ($key == 'id')continue;
            $this->$key = $attr;
        }

    }

    public function equals(Education $education) :bool
    {
        return $this->id == $education->id &&
            $this->degree == $education->degree &&
            $this->graduation_year == $education->graduation_year &&
            $this->institution == $education->institution &&
            $this->study_field == $education->study_field;
    }
    public static function validateEdu($attrs)
    {
        Validator::validate($attrs, [
            'graduation_year' => 'required',
            'institution' => 'required',
            'study_field' => 'required',
            'degree' => 'required'
        ]);
    }

}
