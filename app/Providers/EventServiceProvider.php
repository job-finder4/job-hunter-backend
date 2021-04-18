<?php

namespace App\Providers;

use App\Events\ApplicationEvaluated;
use App\Events\JobadEvaluated;
use App\Listeners\SendApplicationStatusNotification;
use App\Listeners\SendJobadEvaluationStatusNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ApplicationEvaluated::class => [
            SendApplicationStatusNotification::class
        ],
        JobadEvaluated::class => [
            SendJobadEvaluationStatusNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
