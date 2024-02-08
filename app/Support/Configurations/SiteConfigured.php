<?php

namespace App\Support\Configurations;

use App\Concerns\UsesSettings;
use App\Contracts\InteractsWithPersistedStorage;
use App\Contracts\InteractsWithSiteConfigured;
use App\Data\Configuration\SiteConfiguredStateData;

class SiteConfigured implements \Spatie\Onboard\Concerns\Onboardable
{
    use \Spatie\Onboard\Concerns\GetsOnboarded, UsesSettings;
    protected ?SiteConfiguredStateData $state = null;
    protected ?InteractsWithPersistedStorage $persistedStorage = null;

    public function __construct(
    ) {
    }

    protected function storage(): InteractsWithPersistedStorage
    {
        return $this->persistedStorage ??= app(InteractsWithPersistedStorage::class);
    }

    protected function state(): SiteConfiguredStateData
    {
        return $this->state ??= app(SiteConfiguredStateData::class);
    }

    public function wasConfigured(): bool
    {
        return $this->state()->configured();
    }

    public function markSiteConfigured(): void
    {
        $state = $this->state();
        $state->markSiteConfigured();
        $this->storage()->save(path: 'state', data: $state);
    }

    public function wasSiteConfigured(): bool
    {
        return $this->state()->siteConfigured();
    }

    public function markObjectStoreConfigured(): void
    {
        $state = $this->state();
        $state->markBucketConfigured();
        $this->storage()->save(path: 'state', data: $state);
    }

    public function wasObjectStoreConfigured(): bool
    {
        return $this->state()->bucketConfigured();
    }

    public function markDatabaseConfigured(): void
    {
        $state = $this->state();
        $state->markDatabaseConfigured();
        $this->storage()->save(path: 'state', data: $state);
    }

    public function wasDatabaseConfigured(): bool
    {
        return $this->state()->databaseConfigured();
    }
}
