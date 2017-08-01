<?php

namespace jpmurray\LaravelCountdown\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use jpmurray\LaravelCountdown\Countdown;

trait CalculateTimeDiff
{
    /**
     * Return elapsed time based in model attribite
     *
     * @param  string $attribute
     * @return jpmurray\LaravelCountdown\Countdown $countdown
     */
    public function elapsed($attribute)
    {
        $countdown = app('jpmurray.countdown');
        $attribute = $this->{$attribute};
        $now = Carbon::now();

        return $countdown->from($attribute)
                         ->to($now)->get();
    }

    /**
     * Return until time based in model attribite
     *
     * @param  string $attribute
     * @return jpmurray\LaravelCountdown\Countdown $countdown
     */
    public function until($attribute)
    {
        $countdown = app('jpmurray.countdown');
        $attribute = $this->{$attribute};
        $now = Carbon::now();

        return $countdown->from($now)
                         ->to($attribute)->get();
    }
}
