<?php

use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $store = Storage::fake('s3');
    foreach (['alice@localhost', 'bob@localhost', 'bob@localhost/chad@localhost'] as $dir) {
        $store->makeDirectory("/{$dir}");
    }
});

it('lists mailboxes/folders in the root folder', function () {
    $archive_file_system = app(\App\Contracts\ProvidesArchiveFileSystem::class);
    expect($archive_file_system->listMailboxesAvailable())
        ->mailboxes
        ->toHaveCount(2);
});
