<?php

namespace App\Contracts;

use Aws\S3\S3Client;
use Illuminate\Contracts\Filesystem\Filesystem;

interface ProvidesArchiveFileSystem
{
    public function getArchiveStore(): Filesystem;

    public function getArchiveClient(): S3Client;
}
