<?php

namespace App\Contracts;

use App\Support\ArchiveUser;
use GuzzleHttp\Promise\PromiseInterface;

interface ImportExportMailboxContent
{
    public function downloadMailbox(ArchiveUser $mailbox, string $path);

    public function downloadMailboxAsync(ArchiveUser $mailbox, string $path): PromiseInterface;
}
