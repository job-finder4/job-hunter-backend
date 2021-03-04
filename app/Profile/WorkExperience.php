<?php


namespace App\Profile;

use Illuminate\Support\Facades\Date;
use phpseclib\Math\BigInteger;

class WorkExperience
{
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

    public function __construct($job_title, $company_name, $start_date, $end_date, $industry, $job_category, $job_subcategory, $job_description)
    {
        $this->job_title = $job_title;
        $this->company_name = $company_name;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->industry = $industry;
        $this->job_category = $job_category;
        $this->job_subcategory = $job_subcategory;
        $this->job_description = $job_description;
    }

}
