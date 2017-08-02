<?php
namespace Tests;

use Tests\TestCase;
use Carbon\Carbon;
use Countdown;

class CountdownTest extends TestCase
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
     * @dataProvider providerDates
     */
    public function testGetStringForHumansRead($start, $end)
    {
        $countdown = Countdown::from($start->toDateTimeString())
                               ->to($end->toDateTimeString())
                               ->get();

        $this->assertEquals(
            '18 years, 33 weeks, 2 days, 18 hours, 4 minutes and 35 seconds',
            $countdown->toHuman()
        );
    }

    /**
     * @dataProvider providerDates
     * @expectedException jpmurray\LaravelCountdown\Exceptions\InvalidPropertyStringForHumanException
     */
    public function testExceptionStringForHumansHasInvalidProperty($start, $end)
    {
        $countdown = Countdown::from($start->toDateTimeString())
                               ->to($end->toDateTimeString())
                               ->get();

        $customStringForHuman = '{hours} years, {invalid} weeks, {days} days';

        $countdown->toHuman($customStringForHuman);
    }

    /**
     * @dataProvider providerDates
     */
    public function testGetStringForHumansReadWithCustomSentence($start, $end)
    {
        $countdown = Countdown::from($start->toDateTimeString())
                               ->to($end->toDateTimeString())
                               ->get();

        $customStringForHuman = '{hours} years, {weeks} weeks, {days} days';

        $this->assertEquals(
            '18 years, 33 weeks, 2 days',
            $countdown->toHuman($customStringForHuman)
        );
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

    /**
     * @dataProvider providerDates
     * @expectedException jpmurray\LaravelCountdown\Exceptions\InvalidDateFormatToCountdown
     */
    public function testWithInvalidDateToParse($start)
    {
        $countdown = Countdown::from((new \stdClass())) // invalid object or
                               ->to('any invalid string') // invalid string
                               ->get();
        
        $this->makeAsserts($countdown);
    }

    /**
     * @dataProvider providerDates
     */
    public function testWithDateTimeInterface($start)
    {
        $end = new \DateTime('2007-08-14 08:05:10');

        $countdown = Countdown::from($start) // invalid object or
                               ->to($end) // invalid string
                               ->get();
        
        $this->makeAsserts($countdown);
    }

    /**
     * @dataProvider providerDates
     */
    public function testWithTimestampDate($start)
    {
        $countdown = Countdown::from($start)
                               ->to(1187078710)
                               ->get();
        
        $this->makeAsserts($countdown);
    }

    /**
     * @dataProvider providerDates
     */
    public function testWithStandardDateFormat($start)
    {
        $end = '2007-08-14';

        $countdown = Countdown::from($start)
                               ->to($end)
                               ->get();
        
        $this->assertEquals(7, $countdown->years);
        $this->assertEquals(33, $countdown->weeks);
        $this->assertEquals(2, $countdown->days);
        $this->assertEquals(9, $countdown->hours);
        $this->assertEquals(59, $countdown->minutes);
        $this->assertEquals(25, $countdown->seconds);
    }

    /**
     * Testing without dates
     */
    public function testWitDefaultDates()
    {
        $countdown = Countdown::get();
        
        $this->assertEquals(0, $countdown->seconds);
    }
}
