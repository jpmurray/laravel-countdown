<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use jpmurray\LaravelCountdown\Traits\CalculateTimeDiff;

class User extends Model
{
    use CalculateTimeDiff;

    protected $fillable = ['name', 'email', 'password', 'created_at', 'updated_at'];
}
