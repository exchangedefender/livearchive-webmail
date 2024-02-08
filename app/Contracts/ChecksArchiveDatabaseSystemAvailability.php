<?php

namespace App\Contracts;

use App\Support\ServiceAvailabilityOutcome;

interface ChecksArchiveDatabaseSystemAvailability
{
    public function checkArchiveDatabaseAvailability(): ServiceAvailabilityOutcome;
}
