<?php

namespace App\Support\Configurations\Persistence;

enum PersistenceLocation: string
{
    case BROWSER = 'browser';
    case DISK = 'disk';

    public static function default(): self
    {
        return PersistenceLocation::DISK;
    }
}
