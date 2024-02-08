<?php

function layout_site(): App\Support\SiteLayoutStyle
{
    return app(\App\Contracts\ConfiguresSiteRendering::class)->siteLayoutStyle();
}
function layout_effective(): App\Support\SiteLayoutStyle
{
    $override = app(\App\Contracts\OverridesSiteTheme::class);
    $site = app(\App\Contracts\ConfiguresSiteRendering::class);

    return $override->getSiteLayoutOverride() ?? $site->siteLayoutStyle();
}
