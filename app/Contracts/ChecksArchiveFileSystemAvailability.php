<?php

namespace App\Contracts;

use App\Support\ServiceAvailabilityOutcome;

interface ChecksArchiveFileSystemAvailability
{
    public function checkArchiveFileSystemAvailability(): ServiceAvailabilityOutcome;
}
