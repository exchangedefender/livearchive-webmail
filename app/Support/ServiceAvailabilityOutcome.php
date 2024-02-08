<?php

namespace App\Support;

enum ServiceAvailabilityOutcome
{
    case Skipped;
    case Ok;
    case Unhealthy;
}
