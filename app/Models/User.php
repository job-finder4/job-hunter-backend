<?php

namespace App\Models;

use App\Traits\Company;
use App\Traits\JobSeeker;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Company, JobSeeker, Notifiable, HasRoles,HasApiTokens;
    protected $guard_name = 'api';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
