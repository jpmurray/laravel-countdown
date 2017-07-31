<?php

namespace jpmurray\LaravelCountdown;

use Carbon\Carbon;

class Countdown
{
    const WEEKS_PER_YEAR     = 52;
    const DAYS_PER_WEEK      = 7;
    const HOURS_PER_DAY      = 24;
    const MINUTES_PER_HOUR   = 60;
    const SECONDS_PER_MINUTE = 60;
    const SECONDS_PER_HOUR   = 3600;
    const SECONDS_PER_DAY    = 86400;
    const SECONDS_PER_WEEK   = 604800;
    const SECONDS_PER_YEAR   = 31449600;

    private $from = null;
    private $to = null;
    private $delta;
    public $years;
    public $weeks;
    public $days;
    public $hours;
    public $minutes;
    public $seconds;

    /**
     * Sets the time to count from
     * @param   $time
     */
    public function from($time)
    {
        $this->from = $this->convertToCarbon($time);
        return $this;
    }

    /**
     * Sets the time to count to
     * @param   $time
     */
    public function to($time)
    {
        $this->to = $this->convertToCarbon($time);
        return $this;
    }

    /**
     * Returns the object containing the values for the countdown
     * @return object
     */
    public function get()
    {
        if (is_null($this->from)) {
            throw new \Exception("You must at least tell where to count from. ");
        }

        if (is_null($this->to)) {
            $this->to == Carbon::now();
        }

        $this->delta = $this->from->diffInSeconds($this->to);
        
        $this->computeYears()->computeWeeks()->computeDays()->computeHours()->computeMinutes()->computeSeconds();

        return $this;
    }
    /**
     * Takes a time, and if it's a string, try to parse it to a Carbon object
     * @param  $time
     */
    private function convertToCarbon($time)
    {
        if ($time instanceof Carbon) {
            return $time;
        }

        if (is_string($time)) {
            return Carbon::parse($time, config("app.timezone"));
        }

        throw new \Exception("We could not process your time and date.");
    }

    /**
     * Compute the number of seconds for the countdown
     */
    private function computeSeconds()
    {
        $this->seconds = intval(bcmod(intval($this->delta), self::SECONDS_PER_MINUTE));
        return $this;
    }

    /**
     * Compute the number of minutes for the countdown
     */
    private function computeMinutes()
    {
        $this->minutes = intval(bcmod((intval($this->delta) / self::SECONDS_PER_MINUTE), self::MINUTES_PER_HOUR));
        return $this;
    }

    /**
     * Compute the number of hours for the countdown
     */
    private function computeHours()
    {
        $this->hours = intval(bcmod((intval($this->delta) / self::SECONDS_PER_HOUR), self::HOURS_PER_DAY));
        return $this;
    }

    /**
     * Compute the number of days for the countdown
     */
    private function computeDays()
    {
        $this->days = intval(bcmod((intval($this->delta) / self::SECONDS_PER_DAY), self::DAYS_PER_WEEK));
        return $this;
    }

    /**
     * Compute the number of weeks for the countdown
     */
    private function computeWeeks()
    {
        $this->weeks = intval(bcmod((intval($this->delta) / self::SECONDS_PER_WEEK), self::WEEKS_PER_YEAR));
         return $this;
    }

    /**
     * Compute the number of years for the countdown
     */
    private function computeYears()
    {
        $this->years = intval(intval($this->delta) / self::SECONDS_PER_YEAR);
        return $this;
    }
}
