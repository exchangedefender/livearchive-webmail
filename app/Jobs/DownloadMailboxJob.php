<?php

namespace App\Jobs;

use App\Contracts\ImportExportMailboxContent;
use App\Events\DownloadMailboxFinishedEvent;
use App\Exceptions\InvalidEmailAddress;
use App\Support\ArchiveUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DownloadMailboxJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ArchiveUser $mailbox,
        public string $export_path,
    ) {

    }

    public function uniqueId(): string
    {
        return $this->mailbox->username;
    }

    /**
     * Execute the job.
     */
    public function handle(ImportExportMailboxContent $importExportMailboxContent): void
    {
        if (empty($this->mailbox->username)) {
            throw new InvalidEmailAddress('empty email addresses are not allowed for DownloadMailboxJob');
        }

        info("downloading mailbox {$this->mailbox} from s3");

        $importExportMailboxContent->downloadMailbox(
            $this->mailbox,
            $this->export_path,
        );

        info('creating finished file at '.realpath($this->export_path).'.completed');
        if (! file_put_contents(realpath($this->export_path).'/.completed_at', now())) {
            info('error writing completed');
        }

        DownloadMailboxFinishedEvent::dispatch($this->mailbox, $this->export_path);
    }
}
