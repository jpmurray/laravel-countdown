# Laravel countdown

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jpmurray/laravel-countdown/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jpmurray/laravel-countdown/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/jpmurray/laravel-countdown/badges/build.png?b=master)](https://scrutinizer-ci.com/g/jpmurray/laravel-countdown/build-status/master)

The `jpmurray/laravel-countdown` and easy way to get the time difference between two dates, with an extra bonus trait for eloquent.

I needed to get the diffrence of time, and while the [very good Carbon](https://github.com/briannesbitt/carbon) gives me helper to retreive difference in time in different time unit (hours, minutes, etc), there is no method to calculate it all at the same time. Carbon's `diffForHumans` is pretty close, but there is no control over how it displays information, and what information it displays.

## Install

You can install this package via composer:

``` bash
$ composer require jpmurray/laravel-countdown
```

Unless you are using Laravel 5.5 (in which case, package auto-discovery will do it's magic), you will have to add the service provider and facade to your `config/app.php` file.

Edit file: `config/app.php`
```php
'providers' => [
    // ...
    jpmurray\LaravelCountdown\CountdownServiceProvider::class,
    // ...
];

// ...

'aliases' => [
    // ...
    'Countdown' => jpmurray\LaravelCountdown\Facades\CountdownFacade::class,
    // ...
];
```

## Usage

``` php

use jpmurray\LaravelCountdown\Countdown;

// To get time from 5 years ago until now, you can do the following.
// Note that you can send a string to the from and to methods, we will
// try to parse it with Carbon behind the scene
$now = Carbon::now();

$countdown = Countdown::from($now->copy()->subYears(5))
                        ->to($now)->get();

// The above will return the Countdown class where you can access the following values.
// Those mean that from 5 years ago to now, there is 5 years, 1 week, 1 day, 2 hours 15 minutes and 23 seconds

$countdown->years; // 5
$countdown->weeks; // 1
$countdown->days; // 1
$countdown->hours; // 2
$countdown->minutes; // 15
$countdown->seconds; // 23

// It will of course, also work in reverse order of time.
// This will get the time between now and some future date
$countdown = Countdown::from($now)
             ->to($now->copy()->addYears(5))
             ->get();
             
// To return to humans string
$countdown->toHuman(); // 18 years, 33 weeks, 2 days, 18 hours, 4 minutes and 35 seconds

// You to can pass custom string to parse in method toHuman, like this:
$countdown->toHuman('{days} days, {hours} hours and {minutes} minutes'); // 2 days, 18 hours, 4 minutes
```

## Eloquent Trait

```php
// For convenience, we provide a trait that you can add to any model in your Laravel app that provides
// quick methods to get the values of time between dates. For example:

use jpmurray\LaravelCountdown\Traits\CalculateTimeDiff;

class User extends Authenticatable
{
    use Notifiable, CalculateTimeDiff;
    //...
}
```
#### Example to use Trait:
```php
// This enables the following:
// You should have casted your attributes to dates beforehand
$user = User::find(1);
$user->elapsed('trial_ends_at'); // get the time elapsed between the date in attribute trial_ends_at to now
$user->until('trial_ends_at'); // get the time from now until the date in attribute trial_ends_at
```

## Tests

```bash
composer run test
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Jean-Philippe Murray](https://github.com/jpmurray)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
