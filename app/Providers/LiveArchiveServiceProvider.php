<?php

namespace App\Providers;

use App\Contracts\ChecksArchiveDatabaseSystemAvailability;
use App\Contracts\ChecksArchiveFileSystemAvailability;
use App\Contracts\ProvidesArchiveDatabase;
use App\Contracts\ProvidesArchiveFileSystem;
use App\Data\Configuration\BucketSettingsData;
use App\Data\Configuration\LiveArchiveDatabaseSettingsData;
use App\Support\ArchiveDatabase;
use App\Support\ArchiveDatabaseFactory;
use App\Support\ArchiveFileSystem;
use App\Support\Connections\ArchiveDatabaseConnection;
use App\Support\Connections\ArchiveFileSystemConnection;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class LiveArchiveServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(abstract: 'archive.db.factory', concrete: ArchiveDatabaseFactory::class);
        $this->app->bind(abstract: 'archive.db.connection', concrete: ArchiveDatabaseConnection::class);
        $this->app->bind(abstract: 'archive.db', concrete: ArchiveDatabase::class);
        $this->app->bind(abstract: 'archive.disk.connection', concrete: ArchiveFileSystemConnection::class);
        $this->app->bind(abstract: 'archive.disk', concrete: ArchiveFileSystem::class);

        $this->app->when(concrete: ArchiveDatabaseConnection::class)
            ->needs(LiveArchiveDatabaseSettingsData::class)
            ->give(fn() => settings()->databaseSettings());

        $this->app->when(concrete: ArchiveFileSystemConnection::class)
            ->needs(BucketSettingsData::class)
            ->give(fn() => settings()->bucketSettings());


        $this->app->bind(abstract: ArchiveDatabase::class, concrete: ArchiveDatabase::class);
//        $this->app->bind(abstract: ArchiveFileSystem::class, concrete: ArchiveFileSystem::class);
//        $this->app->bind(abstract: ArchiveFileSystem::class, concrete: function ($app) {
//            return new ArchiveFileSystem(
//                filesystem: $app->get('archive.disk.connection'),
//                renderer: $app->get('message_renderer'),
//                providesArchiveDatabase: $app->get(ProvidesArchiveDatabase::class),
//            );
//        });

        $this->app->bind(abstract: ProvidesArchiveFileSystem::class, concrete: ArchiveFileSystem::class);
        $this->app->bind(abstract: ChecksArchiveFileSystemAvailability::class, concrete: ArchiveFileSystem::class);
        $this->app->bind(abstract: ProvidesArchiveDatabase::class, concrete: ArchiveDatabase::class);
        $this->app->bind(abstract: ChecksArchiveDatabaseSystemAvailability::class, concrete: ArchiveDatabase::class);

        //        $this->app->bind(abstract: );
    }

    public function boot(): void
    {
    }
}
