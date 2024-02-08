<?php

namespace App\Contracts;

use Illuminate\Contracts\Foundation\Application;

interface HasRegistrationImplementation
{
    public static function register(Application $app): static;
}
