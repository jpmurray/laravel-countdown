<?php

/*
 * This file is part of the jpmurray\LaravelCountdown package.
 */

namespace jpmurray\LaravelCountdown\Exceptions;

use InvalidArgumentException;

class InvalidDateFormatToCountdown extends InvalidArgumentException
{
    protected $message = 'Invalid date string or object to parse.';
}
