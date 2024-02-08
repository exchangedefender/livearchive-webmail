<?php

namespace App\Support;

use App\Contracts\ChecksArchiveDatabaseSystemAvailability;
use App\Contracts\ProvidesArchiveDatabase;
use App\Support\Connections\ArchiveDatabaseConnection;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\LostConnectionException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ArchiveDatabase implements ChecksArchiveDatabaseSystemAvailability, ProvidesArchiveDatabase
{
    public function __construct(
        protected readonly ArchiveDatabaseConnection $connection
    ) {

    }

    public function getArchiveDatabase(): ?Connection
    {
        return  $this->connection->connection();
    }

    public function hasArchiveDatabase(): bool
    {
        return $this->connection->connection() !== null;
    }

    public function checkArchiveDatabaseAvailability(): ServiceAvailabilityOutcome
    {
        if (! $this->hasArchiveDatabase()) {
            return ServiceAvailabilityOutcome::Skipped;
        } else {
            $connection = $this->getArchiveDatabase();
            if (! $connection) {
                return ServiceAvailabilityOutcome::Unhealthy;
            }
            try {
                return $this->connection->connection()->getSchemaBuilder()->hasTable('messages') ?
                    ServiceAvailabilityOutcome::Ok : ServiceAvailabilityOutcome::Unhealthy;
            } catch (QueryException|LostConnectionException $exception) {
                return ServiceAvailabilityOutcome::Unhealthy;
            }
        }
    }

}
