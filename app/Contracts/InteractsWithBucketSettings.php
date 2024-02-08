<?php

namespace App\Contracts;

use App\Data\Configuration\BucketSettingsData;

interface InteractsWithBucketSettings
{
    public function bucketSettings(): BucketSettingsData;

    public function updateBucketSettings(BucketSettingsData $settings): void;
}
