<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ArchivedMailboxes extends Data
{
    public function __construct(
        #[DataCollectionOf(ArchiveMailboxData::class)]
        public DataCollection $mailboxes
    ) {

    }
}
