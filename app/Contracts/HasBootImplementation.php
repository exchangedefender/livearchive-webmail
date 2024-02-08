<?php

namespace App\Contracts;

use Illuminate\Contracts\Foundation\Application;

interface HasBootImplementation
{
    public static function boot(): void;
}
