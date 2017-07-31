<?php

namespace jpmurray\LaravelCountdown;

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
        $this->app->bind('jpmurray.countdown', function () {
            return new Countdown();
        });

        $this->app->alias('jpmurray.countdown', Countdown::class);
    }
}
