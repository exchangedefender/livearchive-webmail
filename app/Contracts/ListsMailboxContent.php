<?php

namespace App\Contracts;

use App\Data\ArchivedMessages;
use App\Support\ArchiveUser;

interface ListsMailboxContent
{
    public function listMessages(
        ArchiveUser $mailbox,
        int $limit,
    ): ArchivedMessages;

    public function fetchMessage(
        ArchiveUser $mailbox,
        string $message_id,
    ): string;

    public function renderMessage(
        ArchiveUser $mailbox,
        string $message_id,
    ): string;
}
