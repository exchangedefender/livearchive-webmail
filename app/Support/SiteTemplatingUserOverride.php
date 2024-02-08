<?php

namespace App\Support;

use App\Contracts\OverridesSiteTheme;

class SiteTemplatingUserOverride implements OverridesSiteTheme
{
    public function __construct(
        public ?SiteLayoutStyle $siteLayoutStyle = null,
    ) {
    }

    public function getSiteLayoutOverride(): ?SiteLayoutStyle
    {
        return $this->siteLayoutStyle;
    }

    public function getAvailableSiteLayouts(): array
    {
        return collect(SiteLayoutStyle::cases())
            ->map(fn ($x) => $x->value)
            ->filter(fn ($x) => $x !== $this->siteLayoutStyle?->value)
            ->toArray();
    }
}
