<?php

namespace App\Support\Connections;

use App\Contracts\ChecksArchiveDatabaseSystemAvailability;
use App\Data\Configuration\LiveArchiveDatabaseSettingsData;
use App\Support\ServiceAvailabilityOutcome;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Str;

class ArchiveDatabaseConnection
{

    function __construct(
        public readonly LiveArchiveDatabaseSettingsData $settings,
        public readonly string $name = 'livearchive'
    ){}

    function connection(): ?Connection {
        if($this->settings->disabled) {
            return null;
        }
        if(str_contains(':', $this->settings->host)) {
            [$host, $port] = explode(":", $this->settings->host, 2);
        } else {
            $host = $this->settings->host;
            $port = null;
        }
        $factory = app('db.factory');
        return $factory->make([
            'driver' => 'mysql',
            'host' => $host,
            'port' => $port ?: 3306,
            'database' => $this->settings->database,
            'username' => $this->settings->username,
            'password' => $this->settings->password,
        ], $this->name);
    }

}
