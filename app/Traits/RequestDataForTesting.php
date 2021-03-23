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
    public function getProfileDetails()
    {
        return [
            'details' => [
                'phone_number' => '0936689359',
                'location' => [
                    'city' => 'los angeles',
                    'country' => 'usa'
                ],
                'educations' => [
                    [
                        'graduation_year' => 2021,
                        'degree' => 'bachelors',
                        'institution' => 'tishreen university',
                        'study_field' => 'very good'
                    ]
                ],
                'works_experience' => [
                    [
                        'job_title' => 'seo',
                        'company_name' => 'google',
                        'start_date' => '2/2/2010',
                        'end_date' => '2/2/2020',
                        'industry' => 'IT',
                        'job_category' => 'software developer',
                        'job_subcategory' => 'web development',
                        'job_description' => 'it is very easy job to me its very handful',
                    ]
                ],
                'languages' => [
                    'english', 'arabic'
                ],
            ],
            'visibility' => true
        ];
    }


}
