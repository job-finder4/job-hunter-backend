<?php


namespace App\Traits;


use App\Models\Jobad;
use App\Models\Skill;
use Database\Seeders\SkillSeeder;

trait RequestDataForTesting
{
    public function getJobDetails()
    {
        $this->seed(SkillSeeder::class);

        return [
            'title' => 'ceo',
            'min_salary' => 1000,
            'max_salary' => 1500,
            'description' => 'this job require experience in ceo in big company',
            'location' => 'london',
            'job_type' => Jobad::REMOTE,
            'job_time' => Jobad::PART_TIME,
            'salary' => ['min_salary' => 1000, 'max_salary' => 1500],
            'expiration_date' => now()->addMonth(),
            'skills' => Skill::take(2)->get(),
            'approved_at' => now()
        ];
    }


}
