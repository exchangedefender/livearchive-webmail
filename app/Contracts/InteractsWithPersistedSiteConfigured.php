<?php

namespace App\Contracts;

use App\Support\Configurations\Persistence\PersistenceLocation;
use App\Support\Configurations\SiteConfigured;

interface InteractsWithPersistedSiteConfigured
{
    public function save(SiteConfigured $status): void;

    public function restore(): ?SiteConfigured;

    public function location(): PersistenceLocation;
}
