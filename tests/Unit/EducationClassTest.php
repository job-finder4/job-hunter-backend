<?php

namespace Tests\Unit;

use App\Profile\Education;
use App\Profile\UserProfile;
use Illuminate\Support\Str;
use Tests\TestCase;


class EducationClassTest extends TestCase
{
    public function getEducationsAttr()
    {
        return [
                'graduation_year' => 2021,
                'degree' => 'bachelors',
                'institution' => 'tishreen university',
                'study_field' => 'very good'
        ];
    }

    /**
     * @test
    */
    public function make_education_test()
    {
        $eduAttrs = $this->getEducationsAttr();
        $education = Education::make($eduAttrs);

        $this->assertInstanceOf(Education::class, $education);
        $this->assertObjectHasAttribute('id', $education,'education not contain an id');

        $eduAttrs['id'] = $education->id;

        $this->assertJsonStringEqualsJsonString(
            json_encode($eduAttrs),
            json_encode($education)
        );
    }

    /**
     * @test
    */
    public function make_many_educations_method_test()
    {
        $eduAttrs = $this->getEducationsAttr();
        $educations = Education::makeMany($eduAttrs);

        $this->assertIsArray($educations);
        $this->assertCount(1, $educations);
        $this->assertContainsOnlyInstancesOf(Education::class,$educations);
    }

    /**
     * @test
    */
    public function test_if_two_educations_object_are_equals()
    {
        $eduAttr = $this->getEducationsAttr();
        $eduAttr['id'] = Str::uuid();

        $education1 = new Education($eduAttr);
        $education2 = new Education($eduAttr);


        $this->assertTrue($education1->equals($education2));
        $this->assertObjectEquals($education1,$education2,'equals');
    }


}
