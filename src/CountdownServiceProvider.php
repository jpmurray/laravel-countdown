<?php

namespace jpmurray\LaravelCountdown;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use jpmurray\LaravelCountdown\Countdown;

class CountdownServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('jpmurray.countdown', function ($app) {
            $carbon = new Carbon;
            $timezone = $app->config->get('app.timezone');

            return new Countdown($timezone, $carbon);
        });

        $this->app->alias('jpmurray.countdown', Countdown::class);
    }
}
