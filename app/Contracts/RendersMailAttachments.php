<?php

namespace App\Contracts;

use eXorus\PhpMimeMailParser\Parser;
use Spatie\LaravelData\DataCollection;

interface RendersMailAttachments
{
    public function listAttachments(Parser $parser): array|DataCollection;

    public function renderAttachment(Parser $parser, int $id): string;
}
