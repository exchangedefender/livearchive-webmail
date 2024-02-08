<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ArchivedMessages extends Data
{
    public function __construct(
        #[DataCollectionOf(ArchivedMessageData::class)]
        public DataCollection $messages,
        public ?string $continuation_token = null,
        public ?string $return_token = null,
    ) {

    }
}
