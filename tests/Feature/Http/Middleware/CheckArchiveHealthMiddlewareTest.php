<?php

use App\Contracts\InteractsWithPersistedStorage;
use App\Support\Configurations\Persistence\DataStorageLocalFileAdapter;

it('shows health page when the archive file system unavailable', function() {
    $this->mock( \App\Support\Configurations\Persistence\SettingsLocalFileAdapter::class, fn(\Mockery\MockInterface $mock) =>
        $mock->shouldHaveReceived('restore')
        ->andReturn(test_settings())
    );
    $this->mock(\App\Contracts\ChecksArchiveFileSystemAvailability::class, fn(\Mockery\MockInterface $mock) =>
        $mock->shouldReceive('checkArchiveDatabaseAvailability')->andReturn(\App\Support\ServiceAvailabilityOutcome::Unhealthy)
    );
    expect($this->get('/'))
        ->assertRedirectToRoute('health-archive.index');
});
