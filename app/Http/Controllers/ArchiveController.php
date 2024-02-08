<?php

namespace App\Http\Controllers;

use App\Contracts\ListsMailboxes;

class ArchiveController extends Controller
{
    public function index(ListsMailboxes $listsMailboxes)
    {
        return view('mailbox-list', [
            'mailboxes' => $listsMailboxes->listMailboxesAvailable(),
        ]);
    }
}
