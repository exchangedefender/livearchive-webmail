<?php

namespace App\Support;

use App\Support\Connections\ArchiveDatabaseConnection;

class ArchiveDatabaseFactory
{
    public function make(ArchiveDatabaseConnection $connection): ArchiveDatabase {
        return new ArchiveDatabase(connection: $connection);
    }
}
