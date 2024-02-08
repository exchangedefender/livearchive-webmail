<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ArchivedMessageData extends Data
{
    public function __construct(
        public string $path,
        public ?ArchiveMessageMetaData $meta,
    ) {
    }
}
