<?php

namespace Tests\Unit;

use App\Profile\UserProfile;
use App\Profile\WorkExperience;
use Illuminate\Support\Str;
use Tests\TestCase;


class UserProfileClassTest extends TestCase
{
    public function getProfileDetails()
    {
        return [
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
        ];

    }

    public function test_if_two_user_profile_object_are_equals()
    {

        $workAttr = $this->getProfileDetails();
        $userProfile1 = UserProfile::make($workAttr);
        $userProfile2 = $userProfile1;

        $this->assertTrue($userProfile1->equals($userProfile2));
        $this->assertObjectEquals($userProfile1, $userProfile2, 'equals');
    }
}
