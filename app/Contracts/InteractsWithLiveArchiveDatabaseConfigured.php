<?php

namespace App\Contracts;

use App\Data\Configuration\BucketSettingsData;

interface InteractsWithLiveArchiveDatabaseConfigured
{
    public function databaseConfigured(): bool;
    public function markDatabaseConfigured(): void;
    public function resetDatabaseConfigured(): void;
}
