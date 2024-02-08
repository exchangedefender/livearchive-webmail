<?php

use Mockery\MockInterface;

beforeEach(function () {
    //    $this->mock(\App\Support\Configurations\EnvManager::class)

});

it('requires validated input', fn () => expect($this->post(route('setup.site.store')))
    ->assertInvalid(['layout', 'timezone', 'url'])
);

it('redirects after updating site settings', function () {
    $this->mock(\App\Support\Configurations\EnvManager::class, fn (MockInterface $mock) => $mock->shouldReceive('putEnvFile')->once());

    expect($this->post(route('setup.site.store'), ['layout' => 'office', 'timezone' => 'America/New_York', 'url' => 'http://localhost']))
        ->assertRedirectToRoute('setup.index');
});
