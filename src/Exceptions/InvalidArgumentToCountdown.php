<?php

/*
 * This file is part of the jpmurray\LaravelCountdown package.
 */

namespace jpmurray\LaravelCountdown\Exceptions;

use InvalidArgumentException;

class InvalidArgumentToCountdown extends InvalidArgumentException
{
    protected $message = 'You must at least tell where to count from.';
}
