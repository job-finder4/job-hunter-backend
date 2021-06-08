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
        Jobad::factory()->create();
        Jobad::factory()->unapproved()->create();

        $this->assertCount(1, Jobad::all());

        $this->assertCount(1, Jobad::unapproved()->get());
    }

    /**
     * @test
    */
    public function expired_scope_return_expired_jobad()
    {
        Jobad::factory()->create();
        Jobad::factory()->expired()->create();
        $this->assertCount(1, Jobad::all());
        $this->assertCount(1, Jobad::expired()->get());
    }

    /**
     * @test
     */
    public function inactive_scope_return_expired_and_unapprved_jobad()
    {
        Jobad::factory()->create();
        Jobad::factory()->expired()->create();
        Jobad::factory()->unapproved()->create();
        $this->assertCount(1, Jobad::all());
        $this->assertCount(2, Jobad::inactive()->get());
    }

    /**
     * @test
     */
    public function active_and_inactive_scope_return_all_jobad()
    {
        Jobad::factory()->create();
        Jobad::factory()->expired()->create();
        Jobad::factory()->unapproved()->create();
        $this->assertCount(1, Jobad::all());
        $this->assertCount(3, Jobad::activeAndInactive()->get());

    }

}
