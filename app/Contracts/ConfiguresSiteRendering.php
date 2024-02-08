<?php

namespace App\Contracts;

use App\Support\SiteLayoutStyle;

interface ConfiguresSiteRendering
{
    public function siteLayoutStyle(): SiteLayoutStyle;

    public function getAvailableSiteLayouts(?SiteLayoutStyle $except = null): array;
}
