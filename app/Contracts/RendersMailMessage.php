<?php

namespace App\Contracts;

use eXorus\PhpMimeMailParser\Parser;

interface RendersMailMessage
{
    public function parse(string $raw_mime_content): Parser;

    public function render(Parser $parser): string;

    public function renderContent(string $raw_mime_content): string;
}
