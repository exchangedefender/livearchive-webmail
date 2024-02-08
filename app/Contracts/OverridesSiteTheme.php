<?php

namespace App\Contracts;

use App\Support\SiteLayoutStyle;

interface OverridesSiteTheme
{
    public function getSiteLayoutOverride(): ?SiteLayoutStyle;

    public function getAvailableSiteLayouts(): array;
}
