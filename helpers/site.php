<?php

function config_site(): \App\Support\Configurations\SiteConfigured
{
    return app(\App\Support\Configurations\SiteConfigured::class);
}

function settings(): \App\Support\Configurations\SettingsManager
{
    return app(\App\Support\Configurations\SettingsManager::class);
}

function multitenant(): bool {
    return boolval(app('$multitenant'));
}
