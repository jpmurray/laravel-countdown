<?php
namespace Tests;

use Tests\TestCase;
use jpmurray\LaravelCountdown\Countdown;

class LaravelInstanceTest extends TestCase
{
    public function testLaravelHasAliases()
    {
        $instance = app('jpmurray.countdown');

        $this->assertInstanceOf(
            Countdown::class,
            $instance
        );
    }
}
