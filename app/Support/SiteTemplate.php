<?php

namespace App\Support;

use App\Contracts\ConfiguresSiteRendering;

class SiteTemplate implements ConfiguresSiteRendering
{
    public function __construct(
        public SiteLayoutStyle $layout_style
    ) {

    }

    public function siteLayoutStyle(): SiteLayoutStyle
    {
        return $this->layout_style;
    }

    public function getAvailableSiteLayouts(?SiteLayoutStyle $except = null): array
    {
        return collect(SiteLayoutStyle::cases())
            ->map(fn ($x) => $x->value)
            ->filter(fn ($x) => $x !== $except?->value)
            ->toArray();
    }
}
