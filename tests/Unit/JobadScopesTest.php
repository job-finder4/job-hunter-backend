<?php

namespace Tests\Unit;

use App\Models\Jobad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class JobadScopesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function unapproved_scope_return_unapproved_jobad()
    {
        Jobad::factory()->unapproved()->create();

        $this->assertCount(0, Jobad::all());
        $this->assertCount(1, Jobad::unapproved()->get());
    }

    /**
     * @test
    */
    public function expired_scope_return_expired_jobad()
    {
        Jobad::factory()->expired()->create();

        $this->assertCount(0, Jobad::all());
        $this->assertCount(1, Jobad::expired()->get());
    }

}
