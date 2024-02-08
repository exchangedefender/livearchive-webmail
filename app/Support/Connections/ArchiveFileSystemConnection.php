<?php

namespace App\Support\Connections;

use App\Contracts\ChecksArchiveDatabaseSystemAvailability;
use App\Data\Configuration\BucketSettingsData;
use App\Data\Configuration\LiveArchiveDatabaseSettingsData;
use App\Support\ServiceAvailabilityOutcome;
use Aws\S3\S3Client;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Str;

class ArchiveFileSystemConnection
{
    protected ?Filesystem $filesystem = null;

    function __construct(
        public readonly BucketSettingsData $settings,
        public readonly string $name = 'livearchive-disk'
    ){}

    function disk(): Filesystem {

        $factory = app('filesystem');
        return $this->filesystem ??= $factory->build([
            'driver' => 's3',
            'key' => $this->settings->accessKey,
            'secret' => $this->settings->secretAccessKey,
            'region' => $this->settings->region,
            'bucket' => $this->settings->bucket,
//            'url' => $this->settings->ur,
            'endpoint' => $this->settings->endpoint,
            'use_path_style_endpoint' => true,
        ]);
    }

    function s3(): S3Client {

    }

}
