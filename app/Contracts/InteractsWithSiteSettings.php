<?php

namespace App\Contracts;

use App\Data\Configuration\SiteSettingsData;

interface InteractsWithSiteSettings
{
    public function siteSettings(): SiteSettingsData;

    public function updateSettings(SiteSettingsData $settings): void;
}
