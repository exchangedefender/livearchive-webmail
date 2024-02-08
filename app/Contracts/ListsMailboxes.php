<?php

namespace App\Contracts;

use App\Data\ArchivedMailboxes;

interface ListsMailboxes
{
    public function listMailboxesAvailable(): ArchivedMailboxes;
}
