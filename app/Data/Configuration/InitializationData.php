<?php

namespace App\Data\Configuration;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class InitializationData extends Data
{
    public function __construct(
        public Carbon $when,
    )
    {
    }
}
