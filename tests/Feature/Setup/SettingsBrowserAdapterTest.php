<?php

it('saves settings to cookie', function () {
    $settings = settings();
    $settings->siteSettings()->url = 'https://localhost';

    $this->app->bind(\App\Contracts\InteractsWithPersistedSettings::class, \App\Support\Configurations\Persistence\SettingsBrowserAdapter::class);
    $this->app->get(\App\Contracts\InteractsWithPersistedSettings::class)->save($settings->settings());

    expect($this->get('/setup/test'))
        ->assertCookie(cookieName: 'livearchive-settings');
})->todo();
