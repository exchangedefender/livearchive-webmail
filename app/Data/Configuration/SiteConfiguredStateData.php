<?php

namespace App\Data\Configuration;

use App\Contracts\InteractsWithBucketConfigured;
use App\Contracts\InteractsWithConfigured;
use App\Contracts\InteractsWithLiveArchiveDatabaseConfigured;
use App\Contracts\InteractsWithSiteConfigured;
use Spatie\LaravelData\Data;

class SiteConfiguredStateData extends Data implements InteractsWithConfigured, InteractsWithBucketConfigured, InteractsWithLiveArchiveDatabaseConfigured, InteractsWithSiteConfigured
{
    public function __construct(
        public ?bool $site = null,
        public ?bool $bucket = null,
        public ?bool $database = null,
    ) {
    }

    public function configured(): bool
    {
        if($this->database !== null) {
            return $this->database && $this->bucket && $this->site;
        }
        return $this->site && $this->bucket;
    }

    public function siteConfigured(): bool
    {
        return $this->site ?: false;
    }

    public function markSiteConfigured(): void
    {
        $this->site = true;
    }

    public function resetSiteConfigured(): void
    {
        $this->site = false;
    }

    public function databaseConfigured(): bool
    {
        return $this->database ?: false;
    }

    public function markDatabaseConfigured(): void
    {
        $this->database = true;
    }

    public function resetDatabaseConfigured(): void
    {
        $this->database = false;
    }

    public function bucketConfigured(): bool
    {
        return $this->bucket ?: false;
    }

    public function markBucketConfigured(): void
    {
        $this->bucket = true;
    }

    public function resetBucketConfigured(): void
    {
        $this->bucket = false;
    }

}
