<?php

namespace App\Providers;

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
        $this->app->bind('App\ServiceTitan\ServiceTitanApi', function () {
            return new ServiceTitan\ServiceTitanApi();
        });

        $this->app->bind('App\Stripe\StripeApi', function () {
            return new Stripe\StripeApi();
        });

        $this->app->bind('App\KeyManager', function () {
            return new App\KeyManager();
        });

        $this->app->bind('App\Responder', function () {
            return new App\Responder();
        });
    }
}
