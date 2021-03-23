<?php

namespace App\Providers;

use App\Models\Jobad;
use App\Models\Profile;
use App\Models\User;
use App\Policies\JobadPolicy;
use App\Policies\ProfilePolicy;
use Database\Factories\JobadFactory;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Jobad::class => JobadPolicy::class,
        Profile::class => ProfilePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function (User $user) {
            if ($user->hasRole('admin'))
                return true;
        });

        //
    }
}
