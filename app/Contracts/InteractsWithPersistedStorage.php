<?php

namespace App\Contracts;

use App\Data\Configuration\Settings;
use App\Support\Configurations\Persistence\PersistenceLocation;
use Spatie\LaravelData\Contracts\DataObject;
use Spatie\LaravelData\Data;

interface InteractsWithPersistedStorage
{
    public function save(string $path, DataObject $data): void;

    public function restore(string $path, string $dataClass): ?DataObject;

    public function location(): PersistenceLocation;
}
