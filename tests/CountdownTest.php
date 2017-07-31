<?php
namespace Tests;

use Tests\TestCase;
use Carbon\Carbon;
use Countdown;

class CountDownTest extends TestCase
{    
    /**
     * @dataProvider providerDates
     */
    public function testCountdownFromDateTimeString($start, $end)
    {
        $countdown = Countdown::from($start->toDateTimeString())
                               ->to($end->toDateTimeString())
                               ->get();

        $this->makeAsserts($countdown);
    }

    /**
     * [makeAsserts description]
     * @param  \jpmurray\LaravelCountdown\Countdown $countdown [description]
     * @return [type]                                          [description]
     */
    public function makeAsserts(\jpmurray\LaravelCountdown\Countdown $countdown)
    {
        $this->assertEquals(7, $countdown->years);
        $this->assertEquals(33, $countdown->weeks);
        $this->assertEquals(2, $countdown->days); 
        $this->assertEquals(18, $countdown->hours);
        $this->assertEquals(4, $countdown->minutes);
        $this->assertEquals(35, $countdown->seconds); 
    }

    /**
     * @dataProvider providerDates
     */
    public function testCountdownFromCarbonInstance($start, $end)
    {
        $countdown = Countdown::from($start)
                               ->to($end)
                               ->get();

        $this->makeAsserts($countdown);
    }
}
