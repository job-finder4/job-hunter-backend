<?php

namespace App\Traits;


use App\Models\Jobad;

trait Company
{
    public function jobads() {
        return $this->hasMany(Jobad::class);
    }
    public function expiredJobads() {
        return $this->jobads()->expired();
    }
}
