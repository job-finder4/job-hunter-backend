<?php

namespace Tests\Unit;

use App\Profile\Education;
use App\Profile\WorkExperience;
use Illuminate\Support\Str;
use Tests\TestCase;

class WorkExperienceClassTest extends TestCase
{

    public function getWorkExperienceAttr()
    {
        return [
            'job_title' => 'seo',
            'company_name' => 'google',
            'start_date' => '2/2/2010',
            'end_date' => '2/2/2020',
            'industry' => 'IT',
            'job_category' => 'software developer',
            'job_subcategory' => 'web development',
            'job_description' => 'it is very easy job to me its very handful',
        ];
    }

    /**
     * @test
     */
    public function make_work_experience_test()
    {
        $workAttr = $this->getWorkExperienceAttr();
        $workExp = WorkExperience::make($workAttr);

        $this->assertInstanceOf(WorkExperience::class, $workExp);
        $this->assertObjectHasAttribute('id', $workExp, 'education not contain an id');

        $workAttr['id'] = $workExp->id;

        $this->assertJsonStringEqualsJsonString(
            json_encode($workAttr),
            json_encode($workExp)
        );
    }

    /**
     * @test
     */
    public function make_many_works_experience_method_test()
    {
        $workAttrs = $this->getWorkExperienceAttr();
        $worksExperiment = WorkExperience::makeMany($workAttrs);

        $this->assertIsArray($worksExperiment);
        $this->assertCount(1, $worksExperiment);
        $this->assertContainsOnlyInstancesOf(WorkExperience::class, $worksExperiment);
    }

    /**
     * @test
    */
    public function test_if_two_works_experience_object_are_equals()
    {
        $workAttr = $this->getWorkExperienceAttr();
        $workAttr['id'] = Str::uuid();

        $workExp1 = new WorkExperience($workAttr);
        $workExp2 = new WorkExperience($workAttr);

        $this->assertTrue($workExp1->equals($workExp2));
        $this->assertObjectEquals($workExp1,$workExp2,'equals');
    }
}
