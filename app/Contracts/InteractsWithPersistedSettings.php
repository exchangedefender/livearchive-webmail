<?php

namespace App\Contracts;

use App\Data\Configuration\Settings;
use App\Support\Configurations\Persistence\PersistenceLocation;

interface InteractsWithPersistedSettings
{
    public function save(Settings $settings): void;

    public function restore(): ?Settings;

    public function location(): PersistenceLocation;
}
