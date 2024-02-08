<?php

namespace App\Data\Configuration;

use App\Contracts\CachesValidation;
use App\Rules\Data\HostPort;
use Spatie\LaravelData\Attributes\Validation\Accepted;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Prohibited;
use Spatie\LaravelData\Attributes\Validation\Prohibits;
use Spatie\LaravelData\Attributes\Validation\RequiredUnless;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Attributes\Validation\RequiredWithoutAll;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class LiveArchiveDatabaseSettingsData extends Data
{
    //TODO  host parsing port w/ test
    public function __construct(
        #[RequiredWithout('uri'), RequiredUnless('disabled', true), HostPort]
        public string $host,
        #[RequiredWithout('uri'), RequiredUnless('disabled', true)]
        public string $username,
        #[RequiredWithoutAll('uri', 'skipPasswordUpdate', 'disabled'), \SensitiveParameter]
        public ?string $password,
        #[RequiredWithout('uri'), RequiredUnless('disabled', true)]
        public string $database,
        #[Nullable, Prohibits('host', 'username', 'password', 'database')]
        public ?string $uri,
        #[Accepted]
        public Optional|bool $skipPasswordUpdate,
        #[BooleanType]
        public bool $disabled = false,
    ) {
    }



    public static function current(): self
    {
        return self::from([
            'host' => strval(config('database.connections.livearchive.host')),
            'username' => strval(config('database.connections.livearchive.username')),
            'password' => strval(config('database.connections.livearchive.password')),
            'database' => strval(config('database.connections.livearchive.database')),
            'uri' => strval(config('database.connections.livearchive.url')),
        ]);
    }

    public function toEnv(): array
    {
        return [
            'LIVEARCHIVE_DB_HOST' => $this->host,
            'LIVEARCHIVE_DB_DATABASE' => $this->database,
            'LIVEARCHIVE_DB_USERNAME' => $this->username,
            'LIVEARCHIVE_DB_PASSWORD' => $this->password,
            'LIVEARCHIVE_DATABASE_URL' => $this->uri,
        ];
    }

    public static function messages(): array
    {
        return [
            'uri.prohibits' => 'uri cannot be provided with connection parameters',
        ];
    }
    public function toConfig(): array
    {
        [$host, $port] = str_contains($this->host, ':') ?
            explode(":", $this->host, 2) : [$this->host, 3306];
        return [
            'driver' => 'mysql',
            'url' => $this->uri ?: null,
            'host' => $host,
            'port' => $port,
            'database' => $this->database,
            'username' => $this->username,
            'password' => $this->password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ];
    }
}
