<?php

namespace jpmurray\LaravelCountdown;

use Exception;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Foundation\Application;
use jpmurray\LaravelCountdown\Exceptions\InvalidArgumentToCountdown;
use jpmurray\LaravelCountdown\Exceptions\InvalidDateFormatToCountdown;
use jpmurray\LaravelCountdown\Exceptions\InvalidPropertyStringForHumanException;

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
    const STRING_FOR_HUMAN = '{hours} years, {weeks} weeks, {days} days,'
    . ' {hours} hours, {minutes} minutes and {seconds} seconds';

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
     * [__construct description]
     * @param Carbon $carbon [description]
     */
    public function __construct(string $timezone, Carbon $carbon)
    {
        $this->timezone = $timezone;
        $this->carbon = $carbon->now($this->timezone);
    }

    /**
     * Sets the time to count from
     *
     * @param string|integer|DateTime|Carbon $time
     *
     * @return self
     */
    public function from($time) : self
    {
        $this->from = $this->asDateTime($time);

        return $this;
    }

    /**
     * Sets the time to count to
     *
     * @param   string|integer|DateTime|Carbon $time
     *
     * @return  self
     */
    public function to($time = null) : self
    {
        $time ?: $this->carbon;

        $this->to = $this->asDateTime($time);

        return $this;
    }

    /**
     * Returns the object containing the values for the countdown
     *
     * @return object
     */
    public function get()
    {
        if (is_null($this->from)) {
            $this->from = $this->carbon;
        }

        if (is_null($this->to)) {
            $this->to = $this->carbon;
        }

        $this->delta = $this->from->diffInSeconds($this->to);
        
        $this->computeYears()
             ->computeWeeks()
             ->computeDays()
             ->computeHours()
             ->computeMinutes()
             ->computeSeconds();

        return $this;
    }

    /**
     * Return a timestamp as DateTime object.
     *
     * @param  mixed  $value
     *
     * @return \Carbon\Carbon
     */
    protected function asDateTime($value)
    {
        try {
            // If this value is already a Carbon instance, we shall just return it as is.
            // This prevents us having to re-instantiate a Carbon instance when we know
            // it already is one, which wouldn't be fulfilled by the DateTime check.
            if ($value instanceof Carbon) {
                return $value;
            }

            // If the value is already a DateTime instance, we will just skip the rest of
            // these checks since they will be a waste of time, and hinder performance
            // when checking the field. We will just return the DateTime right away.
            if ($value instanceof DateTimeInterface) {
                return $this->carbon->instance($value);
            }

            // If this value is an integer, we will assume it is a UNIX timestamp's value
            // and format a Carbon object from this timestamp. This allows flexibility
            // when defining your date fields as they might be UNIX timestamps here.
            if (is_numeric($value)) {
                return $this->carbon->createFromTimestamp($value);
            }

            // If the value is in simply year, month, day format
            if (is_string($value) && $this->isStandardDateFormat($value)) {
                return $this->carbon->createFromFormat('Y-m-d', $value)->startOfDay();
            }

            // Finally
            return $this->carbon->parse((string)$value);
        } catch (Exception $e) {
            throw new InvalidDateFormatToCountdown;
        }
    }

    /**
     * Determine if the given value is a standard date format.
     *
     * @param  string  $value
     *
     * @return bool
     */
    protected function isStandardDateFormat(string $value) : int
    {
        return preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value);
    }

    /**
     * Compute the number of seconds for the countdown
     *
     * @return  self
     */
    private function computeSeconds() : self
    {
        $this->seconds = intval(bcmod(intval($this->delta), self::SECONDS_PER_MINUTE));

        return $this;
    }

    /**
     * Compute the number of minutes for the countdown
     *
     * @return  self
     */
    private function computeMinutes() : self
    {
        $this->minutes = intval(bcmod((intval($this->delta) / self::SECONDS_PER_MINUTE), self::MINUTES_PER_HOUR));

        return $this;
    }

    /**
     * Compute the number of hours for the countdown
     *
     * @return  self
     */
    private function computeHours() : self
    {
        $this->hours = intval(bcmod((intval($this->delta) / self::SECONDS_PER_HOUR), self::HOURS_PER_DAY));

        return $this;
    }

    /**
     * Compute the number of days for the countdown
     *
     * @return  self
     */
    private function computeDays() : self
    {
        $this->days = intval(bcmod((intval($this->delta) / self::SECONDS_PER_DAY), self::DAYS_PER_WEEK));

        return $this;
    }

    /**
     * Compute the number of weeks for the countdown
     *
     * @return  self
     */
    private function computeWeeks() : self
    {
        $this->weeks = intval(bcmod((intval($this->delta) / self::SECONDS_PER_WEEK), self::WEEKS_PER_YEAR));

        return $this;
    }

    /**
     * Compute the number of years for the countdown
     *
     * @return  self
     */
    private function computeYears() : self
    {
        $this->years = intval(intval($this->delta) / self::SECONDS_PER_YEAR);

        return $this;
    }

    /**
     * Fill string with countdown numbers
     *
     * @param  string $string string for fill
     * @throws \jpmurray\LaravelCountdown\Exceptions\InvalidPropertyStringForHumanException
     * @return string
     */
    private function getStringForHumanRead(string $string) : string
    {
        // search regex
        preg_match_all(
            '/{(.*?)}/',
            $string,
            $matches
        );

        $peaces = $matches[1];
        $filled = [];

        foreach ($peaces as $key => $peace) {
            // Check first class has property
            if (!property_exists($this, $peace)) {
                throw new InvalidPropertyStringForHumanException;
            }

            $filled[$matches[0][$key]] = $this->{$peace};
        }

        $string = str_replace(array_keys($filled), array_values($filled), $string);

        return $string;
    }

    /**
     * Return string with countdown to human read
     *
     * @param string $custom Custom string to parse
     * @return string
     */
    public function toHuman(string $custom = null) : string
    {
        $sentence = ($custom ?: static::STRING_FOR_HUMAN);

        return $this->getStringForHumanRead($sentence);
    }
}
