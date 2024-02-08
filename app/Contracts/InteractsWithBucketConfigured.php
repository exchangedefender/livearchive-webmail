<?php

namespace App\Contracts;

use App\Data\Configuration\BucketSettingsData;

interface InteractsWithBucketConfigured
{
    public function bucketConfigured(): bool;
    public function markBucketConfigured(): void;
    public function resetBucketConfigured(): void;
}
