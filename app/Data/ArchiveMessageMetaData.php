<?php

namespace App\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class ArchiveMessageMetaData extends Data
{
    public function __construct(
        public string $recipient,
        public string $sender,
        public string $sender_envelope,
        public string $subject,
        #[WithCast(DateTimeInterfaceCast::class, timeZone: 'UTC')]
        public Carbon $timestamp,
        public int $attachment_count,
        public string $preview,

    ) {
    }
}
