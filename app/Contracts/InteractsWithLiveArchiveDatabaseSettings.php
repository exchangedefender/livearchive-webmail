<?php

namespace App\Contracts;

use App\Data\Configuration\BucketSettingsData;
use App\Data\Configuration\LiveArchiveDatabaseSettingsData;
use App\Data\Configuration\SiteSettingsData;

interface InteractsWithLiveArchiveDatabaseSettings
{
    public function databaseSettings(): LiveArchiveDatabaseSettingsData;

    public function updateDatabaseSettings(LiveArchiveDatabaseSettingsData $settings): void;
}
