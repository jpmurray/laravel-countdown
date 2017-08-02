<?php

/*
 * This file is part of the jpmurray\LaravelCountdown package.
 */

namespace jpmurray\LaravelCountdown\Exceptions;

use InvalidArgumentException;

class InvalidPropertyStringForHumanException extends InvalidArgumentException
{
    protected $message = 'String to parse for human contains invalid property';
}
