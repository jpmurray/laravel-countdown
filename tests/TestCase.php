<?php
namespace Tests;

use Carbon\Carbon;
use jpmurray\LaravelCountdown\CountdownServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CountdownServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Countdown' => \jpmurray\LaravelCountdown\Facades\CountdownFacade::class,
        ];
    }

    /**
     * Provider data for tests
     * @return array $dates
     */
    public function providerDates()
    {
        $startDate = Carbon::parse('2000-01-01 14:00:35');
        $endDate = Carbon::parse('2007-08-14 08:05:10');

        return [[$startDate, $endDate]];
    }
}
