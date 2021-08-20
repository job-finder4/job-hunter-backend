<?php

namespace App\Providers;


use App\BankingGateway;
use App\CreditCardGateWay;
use App\PaymentGateWay;
use App\PostCardSendService;
use Illuminate\Auth\Notifications\ResetPassword;
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

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return config('app.url') . '/reset-password?token=' . $token . '&email=' . urlencode($notifiable->email);
        });

    }
}
