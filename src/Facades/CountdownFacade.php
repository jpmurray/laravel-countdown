<?php

namespace jpmurray\LaravelCountdown\Facades;

use Illuminate\Support\Facades\Facade;

class CountdownFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'jpmurray.countdown';
    }
}
