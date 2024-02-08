<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class ArchivedMessageAttachmentData extends Data
{
    public function __construct(
        public int $id,
        public string $filename,
        public string $contentType,
        public string $contentDisposition,
        public string $contentId,
    ) {
    }
}
