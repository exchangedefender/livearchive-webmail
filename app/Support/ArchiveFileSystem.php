<?php

namespace App\Support;

use App\Contracts\ChecksArchiveFileSystemAvailability;
use App\Contracts\ImportExportMailboxContent;
use App\Contracts\ListsMailboxContent;
use App\Contracts\ListsMailboxes;
use App\Contracts\ProvidesArchiveDatabase;
use App\Contracts\ProvidesArchiveFileSystem;
use App\Contracts\RendersMailMessage;
use App\Data\ArchivedMailboxes;
use App\Data\ArchivedMessageData;
use App\Data\ArchivedMessages;
use App\Data\ArchiveMailboxData;
use App\Data\ArchiveMessageMetaData;
use App\Exceptions\MessageFileNotFound;
use App\Support\Connections\ArchiveFileSystemConnection;
use Aws\S3\S3Client;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Connection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ArchiveFileSystem implements ChecksArchiveFileSystemAvailability, ImportExportMailboxContent, ListsMailboxContent, ListsMailboxes, ProvidesArchiveFileSystem
{
    protected ?S3Client $client = null;

    public function __construct(
        protected ArchiveFileSystemConnection $filesystem,
        protected RendersMailMessage $renderer,
        protected ?ProvidesArchiveDatabase $providesArchiveDatabase,
    ) {
    }

    public function getArchiveStore(): Filesystem
    {
        return $this->filesystem->disk();
    }

    public function getArchiveClient(): S3Client
    {
        return $this->s3();
    }

    protected function s3(): S3Client {
        return $this->client ??= new S3Client([
            'region' => $this->filesystem->settings->region,
            'endpoint' => $this->filesystem->settings->endpoint,
            'use_path_style_endpoint' => true,
            'credentials' => filled($this->filesystem->settings->secretAccessKey) ? [
                'key' => $this->filesystem->settings->accessKey,
                'secret' => $this->filesystem->settings->secretAccessKey,
            ] : false,
        ]);
    }

    public function listMailboxesAvailable(): ArchivedMailboxes
    {
        return ArchivedMailboxes::from([
            'mailboxes' => collect($this->filesystem->disk()->directories('/'))
                ->filter(fn($dir) => filter_var($dir, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) !== null)
                ->map(fn($dir) => ArchiveMailboxData::from(['email' => ArchiveUser::from_string($dir)]))
                ->toArray(),
        ]);

    }

    public function listMessages(ArchiveUser $mailbox, int $limit = 20, ?string $continuation_token = null, ?\Closure $filter = null): ArchivedMessages
    {

        if ($database = $this->providesArchiveDatabase->getArchiveDatabase()) {
            [$prev, $next, $result] = $this->listMessagesUsingDatabase($database, $mailbox, $limit, $continuation_token, $filter);
        } else {
            [$prev, $next, $result] = $this->listMessagesUsingS3($mailbox, $limit, $continuation_token);
        }

        //        //TODO remove.. just here for previewing timestamp with relative time
        //        if (empty($continuation_token)) {
        //            $result[0]->meta->timestamp = now()->subMinute()->subSeconds(15);
        //            $result[1]->meta->timestamp = now()->subMinutes(5)->subSeconds(30);
        //            $result[2]->meta->timestamp = now()->subMinutes(65)->subSeconds(30);
        //            $result[3]->meta->timestamp = now()->subMinutes(85)->subSeconds(30);
        //            $result[4]->meta->timestamp = now()->subDay()->subSeconds(30);
        //            $result[5]->meta->timestamp = now()->subDays(2)->subSeconds(30);
        //        }

        return ArchivedMessages::from([
            'return_token' => $prev,
            'continuation_token' => $next,
            'messages' => $result,
        ]);

    }

    /**
     * @throws MessageFileNotFound
     */
    public function fetchMessage(ArchiveUser $mailbox, string $message_id): string
    {
        $path = str_starts_with($message_id, $mailbox->username) ? $message_id : "{$mailbox}/Maildir/new/$message_id";
        $found = $this->getArchiveStore()->get($path);
        if ($found === null) {
            throw MessageFileNotFound::for($path);
        }

        return $found;
    }

    public function renderMessage(ArchiveUser $mailbox, string $message_id): string
    {
        return $this->renderer->renderContent($this->fetchMessage($mailbox, $message_id));
    }

    public function downloadMailboxAsync(ArchiveUser $mailbox, string $path): PromiseInterface
    {
        return $this->s3()->downloadBucketAsync(
            directory: $path,
            bucket: $this->bucket,
            options: [
                'base_dir' => "$mailbox",
                'concurrency' => 5,
            ]
        );
    }

    public function downloadMailbox(ArchiveUser $mailbox, string $path)
    {
        $this->s3()->downloadBucket(
            directory: $path,
            bucket: $this->bucket,
            keyPrefix: "$mailbox/",
            options: [
                'concurrency' => 5,
            ]
        );
    }

    private function listMessagesUsingDatabase(Connection $database, ArchiveUser $mailbox, int $limit, ?string $cursor, ?\Closure $filter = null)
    {

        $result = $database->table('messages')
            ->where('recipient', $mailbox->username)
            ->orderBy('timestamp', 'desc');
        if ($filter) {
            $result = $result->where($filter);
        }
        $result = $result->cursorPaginate($limit, columns: ['id', 'sender', 'sender_envelope', 'recipient', 'subject', 'timestamp', 'file_path', 'attachment_count', 'preview'], cursor: $cursor);

        $mapped = collect($result->items())
            ->map(function ($row) {
                return ArchivedMessageData::from([
                    'path' => $row->file_path,
                    'meta' => [
                        'recipient' => $row->recipient,
                        'sender' => $row->sender,
                        'sender_envelope' => $row->sender_envelope,
                        'subject' => $row->subject,
                        'timestamp' => Carbon::parse($row->timestamp),
                        'attachment_count' => (int) $row->attachment_count,
                        'preview' => $row->preview,
                    ],
                ]);
            });

        return [
            $result->previousCursor()?->encode() ?: null,
            $result->nextCursor()?->encode() ?: null,
            $mapped,
        ];

    }

    private function listMessagesUsingS3(ArchiveUser $mailbox, int $limit, ?string $continuation_token)
    {
        $optional = [];
        if (! empty($continuation_token)) {
            $optional['ContinuationToken'] = $continuation_token;
        }
        $results = $this->s3()->listObjectsV2([
            ...$optional,
            'Bucket' => $this->filesystem->settings->bucket,
            'Prefix' => "{$mailbox}/Maildir/new",
            'MaxKeys' => $limit,
        ])->toArray();

        //        dd($results);

        $result = Collection::make(data_get($results, 'Contents', []))
            ->map(function (array $props) {
                ['Key' => $key] = $props;
                //get metadata from the files from S3
                $meta = $this->getMessageMetaData($key);

                return ArchivedMessageData::from([
                    'path' => $key,
                    'meta' => $meta,
                ]);
            });

        return [data_get($results, 'ContinuationToken'), data_get($results, 'NextContinuationToken'), $result];
    }

    public function getMessageMetaData(string $path): ?ArchiveMessageMetaData
    {
        if ($this->providesArchiveDatabase->hasArchiveDatabase()) {
            return $this->getMessageMetaDataDatabase($this->providesArchiveDatabase->getArchiveDatabase(), $path);
        } else {
            return $this->getMessageMetaDataS3($path);
        }
    }

    public function getMessageMetaDataS3(string $path): ?ArchiveMessageMetaData
    {
        try {
            $result = $this->s3()
                ->getObject([
                    'Bucket' => $this->filesystem->settings->bucket,
                    'Key' => $path,
                ]);
            $meta = $result->get('@metadata')['headers'];
            data_fill($meta, 'x-amz-meta-message-from', '');
            data_fill($meta, 'x-amz-meta-message-sender-envelope-b64', '');
            data_fill($meta, 'x-amz-meta-message-to', '');
            data_fill($meta, 'x-amz-meta-message-subject', '');
            data_fill($meta, 'x-amz-meta-message-date', now());
            data_fill($meta, 'x-amz-meta-message-epoch', '');
            data_fill($meta, 'x-amz-meta-message-preview', '');
            data_fill($meta, 'x-amz-meta-message-preview-b64', '');
            data_fill($meta, 'x-amz-meta-message-attachment-count', '0');

            if (filled(data_get($meta, 'x-amz-meta-message-preview-b64'))) {
                $preview = base64_decode($meta['x-amz-meta-message-preview-b64']);
            } else {
                $preview = $meta['x-amz-meta-message-preview'];
            }

            return ArchiveMessageMetaData::from([
                'recipient' => $meta['x-amz-meta-message-from'] ?: '',
                'sender_envelope' => base64_decode($meta['x-amz-meta-message-sender-envelope-b64']) ?: '',
                'sender' => $meta['x-amz-meta-message-to'] ?: '',
                'subject' => $meta['x-amz-meta-message-subject'] ?: '',
                'timestamp' => $meta['x-amz-meta-message-date'] ?: '',
                'preview' => $preview ?: '',
                'attachment_count' => (int) ($meta['x-amz-meta-message-attachment-count'] ?: ''),
            ]);
        } catch (\Exception $e) {
        }

        return null;
    }

    public function getMessageMetaDataDatabase(Connection $database, string $path): ?ArchiveMessageMetaData
    {
        $result = $database->table('messages')
            ->where('file_path', $path)
            ->first();

        return $result ? ArchiveMessageMetaData::from([
            'subject' => $result->subject,
            'sender_envelope' => $result->sender_envelope,
            'sender' => $result->sender,
            'recipient' => $result->recipient,
            'timestamp' => Carbon::parse($result->timestamp),
            'preview' => $result->preview,
            'attachment_count' => (int) $result->attachment_count,
        ]) : null;
    }

    public function checkArchiveFileSystemAvailability(): ServiceAvailabilityOutcome
    {
        try {
            if ($this->s3()->doesBucketExist($this->filesystem->settings->bucket)) {
                return ServiceAvailabilityOutcome::Ok;
            } else {
                return ServiceAvailabilityOutcome::Unhealthy;
            }
        } catch (\Exception $e) {
            return ServiceAvailabilityOutcome::Unhealthy;
        }
    }
}
