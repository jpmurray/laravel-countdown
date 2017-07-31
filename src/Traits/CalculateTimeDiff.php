<?php

namespace jpmurray\LaravelCountdown\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

trait CalculateTimeDiff
{
    public function elapsed($attribute)
    {
        return \Countdown::from($this->{$attribute})->to(Carbon::now())->get();
    }

    public function until($attribute)
    {
        return \Countdown::from(Carbon::now())->to($this->{$attribute})->get();
    }
}
