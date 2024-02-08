<?php

namespace App\Data;

use App\Support\ArchiveUser;
use Spatie\LaravelData\Data;

class ArchiveMailboxData extends Data
{
    public function __construct(
        public ArchiveUser $email,
    ) {
    }
}
