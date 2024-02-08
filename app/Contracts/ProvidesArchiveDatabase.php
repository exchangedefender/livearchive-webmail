<?php

namespace App\Contracts;

use Illuminate\Database\Connection;

interface ProvidesArchiveDatabase
{
    public function getArchiveDatabase(): ?Connection;

    public function hasArchiveDatabase(): bool;
}
