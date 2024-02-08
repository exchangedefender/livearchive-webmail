<?php

it('redirects to setup.site when site is not configured', fn () => expect($this->get(route('setup.index')))
    ->assertRedirectToRoute('setup.site')
);

it('redirects to setup.bucket when site is configured', function () {
    //    Cache::shouldReceive('get')
    //        ->once()
    //        ->with(\App\Data\Configuration\SiteSettingsData::class)
    //        ->andReturn(false);
    //    $spy = Cache::spy();

    $site = app(\App\Support\Configurations\SiteConfigured::class);
    //    Cache::spy(); //Waiting on https://github.com/spatie/laravel-data/pull/645
    $site->markSiteConfigured();

    //    $spy->shouldHaveReceived('put')
    //        ->once()
    //        ->with(\App\Data\Configuration\SiteSettingsData::class, \Mockery::type('callable'));

    expect($this->get(route('setup.index')))
        ->assertRedirectToRoute('setup.bucket');
});

//it('redirects to setup.database when bucket is configured', function() {
//
//    $site = app(\App\Support\Configurations\SiteConfigured::class);
//    $site->markSiteConfigured();
//
//    expect($this->get(route('setup.index')))
//        ->assertRedirectToRoute('setup.bucket');
//});

it('redirects to setup.database when bucket is configured', function () {

    $site = config_site();
    $site->markSiteConfigured();
    $site->markObjectStoreConfigured();

    expect($this->get(route('setup.index')))
        ->assertRedirectToRoute('setup.database');
});

it('redirects home when site is configured', function () {

    $site = config_site();
    $site->markSiteConfigured();
    $site->markObjectStoreConfigured();
    $site->markDatabaseConfigured();

    expect($this->get(route('setup.index')))
        ->assertRedirectToRoute('home');
});
