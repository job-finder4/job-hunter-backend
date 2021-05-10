<?php

namespace App\Providers;


use App\Interview\IntervalBasedScheduler;
use App\Interview\InterviewManager;
use App\Interview\InterviewManagerContract;
use App\Interview\SchedulerContract;
use App\Interview\UserInterviews\CompanyInterviews;
use App\Interview\UserInterviews\JobSeekerInterviews;
use App\Interview\UserInterviews\UserInterviews;
use App\Models\Jobad;
use  Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(InterviewManagerContract::class, function () {
            $jobad = $this->app->request->interviewAbleJobad;
            return new InterviewManager(new IntervalBasedScheduler($jobad), $jobad);
        });

        $this->app->bind(UserInterviews::class, function () {
            if (auth()->user()->hasRole('jobSeeker')) {
                return new JobSeekerInterviews();
            }
            return new CompanyInterviews();
        });

        $this->app->bind(SchedulerContract::class, function () {
            return new IntervalBasedScheduler(
                $this->app->request->interviewAbleJobad
            );
        });

        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return config('app.url') . '/reset-password?token=' . $token . '&email=' . urlencode($notifiable->email);
        });
    }
}
