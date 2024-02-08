<?php

namespace App\Concerns;

use App\Support\Configurations\SettingsManager;

trait UsesSettings
{
    protected ?SettingsManager $settingsManager = null;

    public function settingsManager()
    {
        return $this->settingsManager ??= app(SettingsManager::class);
    }
}
