<?php
namespace Tests;

use Faker;
use Tests\TestCase;
use Carbon\Carbon;
use jpmurray\LaravelCountdown\Countdown;
use Tests\User;

class CountdownEloquentTraitTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'testing']);

        $this->faker = Faker\Factory::create();
    }

    /**
     * Test running migration.
     * @dataProvider providerDates
     * @test
     */
    public function testEloquentTrait($date)
    {
        $user = User::create([
            'name'       => $this->faker->name,
            'email'      => $this->faker->email,
            'password'   => $this->faker->password,
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        $this->assertInstanceOf(Countdown::class, $user->elapsed('created_at'));
        $this->assertInstanceOf(Countdown::class, $user->until('created_at'));
    }
}
