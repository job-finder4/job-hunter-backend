<?php


namespace App\Profile;

use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use phpseclib\Math\BigInteger;
use Psy\Util\Str;

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


    public function __construct($graduation_year, $institution, $study_field, $degree)
    {
        $this->graduation_year = $graduation_year;
        $this->institution = $institution;
        $this->study_field = $study_field;
        $this->degree = $degree;
    }

    public function availableYears(){
        return range(now()->year-20, now()->year);
    }

    public static function createMany(array $eduAttrs)
    {
        $collection = collect($eduAttrs);
        $collection->each(function ($attr){

        });
    }
    public function create($attrs)
    {

    }

}
