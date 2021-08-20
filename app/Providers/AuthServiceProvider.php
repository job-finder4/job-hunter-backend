<?php

namespace App\Providers;

use App\Models\Cv;
use App\Models\Jobad;
use App\Models\Profile;
use App\Models\User;
use App\Policies\CvPolicy;
use App\Policies\JobadPolicy;
use App\Policies\ProfilePolicy;
use Database\Factories\JobadFactory;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',

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

        Passport::routes();

        VerifyEmail::createUrlUsing(function ($notifiable) {
            return remove_api_segment(URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            ));
        });
    }
}
