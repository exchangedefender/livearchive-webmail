<?php

namespace App\Http\Controllers;

use App\Contracts\ProvidesArchiveDatabase;
use App\Contracts\ProvidesArchiveFileSystem;
use App\Contracts\RendersMailAttachments;
use App\Contracts\RendersMailMessage;
use App\Data\ArchivedMessageData;
use App\Data\ArchivedMessages;
use App\Data\ArchiveMessageFilterData;
use App\Exceptions\MessageFileNotFound;
use App\Jobs\DownloadMailboxJob;
use App\Support\ArchiveFileSystem;
use App\Support\ArchiveUser;
use App\Support\SiteLayoutStyle;
use App\Support\SiteTemplate;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Mauricius\LaravelHtmx\Facades\HtmxResponse;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Zip;

class ArchiveInboxController extends Controller
{
    const RESULTS_PER_PAGE = 25;

    public function __construct(
        public RendersMailMessage $rendersMailMessage,
        public RendersMailAttachments $rendersMailAttachments,
    ) {
    }

    public function index(
        HtmxRequest $htmxRequest,
        ArchiveFileSystem $archive_file_system,
        ProvidesArchiveDatabase $providesArchiveDatabase,
        SiteTemplate $siteTemplate,
        ?ArchiveMessageFilterData $filterInbox,
        string $mailbox,
        ?string $message = null,
    ) {
        $mailbox = ArchiveUser::from_string($mailbox);

        $search = request()->query('search');

        $mailbox = ArchiveUser::from_string($mailbox);
        if (! empty($message)) {
            $decrypted_message = Crypt::decryptString($message);
        } else {
            $decrypted_message = null;
        }

        if (! $filterInbox?->enabled && request()->has('search') && empty($search)) {
            if ($htmxRequest->isHtmxRequest()) {
                return new HtmxResponseClientRedirect(route('mailbox.inbox', ['mailbox' => $mailbox], false));
            } else {
                return to_route('mailbox.inbox', ['mailbox' => $mailbox]);
            }
        }

        abort_if(filled($search) && ! $providesArchiveDatabase->hasArchiveDatabase(), 500, 'search is not supported without an archive database enabled');

        if (request()->has('continuation_token')) {
            $continuation_token = decrypt(request()->get('continuation_token'));
        } else {
            $continuation_token = null;
        }

        if ($filterInbox->enabled) {
            $filter = function (Builder $builder) use ($filterInbox, $search) {
                ['sender' => $sender, 'date' => $date, 'subject' => $subject] = $filterInbox->toArray();
                if (filled($sender)) {
                    $builder
                        ->where('sender', 'like', "%{$sender}%", 'or')
                        ->orWhere('sender_envelope', 'like', "%{$sender}%");
                }
                if (filled($subject)) {
                    $builder
                        ->where('subject', 'like', "%{$subject}%");
                }
                if (filled($date)) {
                    [$start, $end] = $filterInbox->dates();
                    $builder
                        ->where('timestamp', '>=', $start->utc())
                        ->where('timestamp', '<=', $end->utc());

                }

                if (filled($search)) {
                    $builder->whereNested(fn ($builder) => $builder->where('sender', 'LIKE', "%{$search}%")
                        ->orWhere('sender_envelope', 'LIKE', "%{$search}%")
                        ->orWhere('subject', 'LIKE', "%{$search}%"));
                }
            };
        } elseif (filled($search)) {
            $filter = function (Builder $builder) use ($search) {
                return $builder->where('sender', 'LIKE', "%{$search}%")
                    ->orWhere('sender_envelope', 'LIKE', "%{$search}%")
                    ->orWhere('subject', 'LIKE', "%{$search}%");
            };
        } else {
            $filter = null;
        }

        $messages = $archive_file_system->listMessages(
            $mailbox,
            limit: self::RESULTS_PER_PAGE,
            continuation_token: $continuation_token,
            filter: $filter,
        );
        $page_url_prev = filled($messages->return_token) ? route('mailbox.inbox', ['continuation_token' => encrypt($messages->return_token), 'mailbox' => $mailbox]) : null;
        $page_url_next = filled($messages->continuation_token) ? route('mailbox.inbox', ['continuation_token' => encrypt($messages->continuation_token), 'mailbox' => $mailbox]) : null;

        if ($continuation_token !== null && $htmxRequest->isHtmxRequest()) {
            if (
                ! $providesArchiveDatabase->hasArchiveDatabase() &&
                $siteTemplate->layout_style === SiteLayoutStyle::GMAIL &&
                $continuation_token === $messages->return_token) {
                //TODO ugly fix for S3 pagination.. cant figure out return urls so sending them back to the start :)
                $page_url_prev = route('mailbox.inbox', ['mailbox' => $mailbox]);
            }

            return HtmxResponse::addFragment('fragments/inbox-'.$siteTemplate->layout_style->value, 'messages-list', [
                'mailbox' => $mailbox,
                'filter' => $filterInbox,
                'messages' => $messages,
                'selected_message' => $decrypted_message,
                'selected_message_encrypted' => $message,
                'search' => $search,
            ])
                ->addFragment('fragments/inbox-'.$siteTemplate->layout_style->value, 'messages-meta', [
                    'mailbox' => $mailbox,
                    'page_url_prev' => $page_url_prev ?? route('mailbox.inbox', ['mailbox' => $mailbox]),
                    'page_url_next' => $page_url_next,
                    'page_url_push' => false, //!$providesArchiveDatabase->hasArchiveDatabase(),
                ]);
        } else {

            return view('inbox', [
                'mailbox' => $mailbox,
                'filter' => $filterInbox,
                'messages' => $messages,
                'selected_message' => $decrypted_message,
                'selected_message_encrypted' => $message,
                'page_url_prev' => $page_url_prev,
                'page_url_next' => $page_url_next,
                'page_url_push' => false, //!$providesArchiveDatabase->hasArchiveDatabase(),
                'search' => $search,
            ]);
        }

    }

    public function view(
        HtmxRequest $request,
        ArchiveFileSystem $archive_file_system,
        ?ArchiveMessageFilterData $filterInbox,
        string $mailbox,
        string $message
    ) {
        $compat = request()->boolean('compat');
        if (layout_effective() === SiteLayoutStyle::OFFICE && ! $request->isHtmxRequest() && ! $compat) {
            return to_route('mailbox.inbox', ['mailbox' => $mailbox, 'message' => $message]);
        }
        $mailbox = ArchiveUser::from_string($mailbox);
        $message = Crypt::decryptString($message);
        try {
            $parsed = $this->rendersMailMessage->parse($archive_file_system->fetchMessage($mailbox, $message));
            $meta = $archive_file_system->getMessageMetaData($message);

            return view('message-view', [
                'compat' => $compat,
                'mailbox' => $mailbox,
                'message' => Crypt::encryptString($message),
                'filter' => $filterInbox,
                'message_path' => $message,
                'message_from' => $meta?->sender_envelope ?: $meta->sender ?: $parsed->getHeader('from'),
                'message_to' => $meta?->recipient ?: $parsed->getHeader('to'),
                'message_subject' => $meta?->subject ?: $parsed->getHeader('subject'),
                'message_date' => $meta?->timestamp ?: $parsed->getHeader('date'),
                'message_body' => $this->rendersMailMessage->render($parsed),
                'message_attachments' => $this->rendersMailAttachments->listAttachments($parsed),
                'action_fullscreen' => $request->isHtmxRequest(),
            ]);
        } catch (MessageFileNotFound $exception) {
            return response()->view('error', [
                'issue' => 'Message not found',
                'message' => $exception->getMessage(),
                'container_id' => 'message-view-content',
                'mailbox' => $mailbox,
            ], $request->isHtmxRequest() ? 200 : 404);
        }
    }

    public function render(HtmxRequest $request, ArchiveFileSystem $archive_file_system, string $mailbox, string $message)
    {
        //        abort_if(!$request->isHtmxRequest(), 500, 'outside render is not supported');
        $mailbox = ArchiveUser::from_string($mailbox);
        $messages = $archive_file_system->listMessages($mailbox);
        $message = Crypt::decryptString($message);

        try {
            $parsed = $this->rendersMailMessage->parse($archive_file_system->fetchMessage($mailbox, $message));

            return response($this->rendersMailMessage->render($parsed));
        } catch (MessageFileNotFound $exception) {
            return response($exception->getMessage(), 404);
        }
    }

    public function search(ProvidesArchiveDatabase $providesArchiveDatabase, string $mailbox)
    {
        $search = request()->input('search');
        $mailbox = ArchiveUser::from_string($mailbox);
        abort_unless($providesArchiveDatabase->hasArchiveDatabase(), 500, 'search is not supported without an archive database enabled');
        $results = $providesArchiveDatabase->getArchiveDatabase()
            ->table('messages')
            ->where('recipient', $mailbox->username)
            ->where('sender', 'LIKE', "%{$search}%")
            ->orWhere('sender_envelope', 'LIKE', "%{$search}%")
            ->orWhere('subject', 'LIKE', "%{$search}%")
            ->orderBy('timestamp', 'DESC')
            ->get();

        $mapped = $results
            ->map(function ($row) {
                return ArchivedMessageData::from([
                    'path' => $row->file_path,
                    'meta' => [
                        'recipient' => $row->sender,
                        'sender' => $row->recipient,
                        'subject' => $row->subject,
                        'timestamp' => Carbon::parse($row->timestamp),
                        'attachment_count' => (int) $row->attachment_count,
                        'preview' => $row->preview,
                    ],
                ]);
            });

        $messages = ArchivedMessages::from([
            'continuation_token' => null,
            'return_token' => null,
            'messages' => $mapped,
        ]);

        return view()->renderFragment('inbox', 'messages-list', [
            'mailbox' => $mailbox,
            'messages' => $messages,
            'selected_message' => null,
        ]);

    }

    public function download(HtmxRequest $request, ArchiveUser $mailbox)
    {
        $path = public_path("storage/exports/{$mailbox}");

        if (request()->isMethod('POST')) {
            DownloadMailboxJob::dispatchAfterResponse($mailbox, $path);
            if ($request->isHtmxRequest()) {
                return view('inbox-download', ['mailbox' => $mailbox]);
            } else {
                return to_route('mailbox.download', ['mailbox' => $mailbox]);
            }
        } else {
            if (! file_exists($path)) {
                abort(404);
            } elseif (! file_exists($path.'/.completed_at')) {
                return view('inbox-download', [
                    'mailbox' => $mailbox,
                    'download_link' => route('mailbox.download', ['mailbox' => $mailbox]),
                ]);
            } else {
                if ($request->isHtmxRequest()) {
                    return view('inbox-download', [
                        'mailbox' => $mailbox,
                        'download_link' => route('mailbox.download', ['mailbox' => $mailbox]),
                    ]);
                    //                        ->withStatus(286)
                    //return new HtmxResponseClientRedirect(route('mailbox.download', ['mailbox', $mailbox]));
                } else {
                    $zip = Zip::create("{$mailbox}.zip");
                    foreach (Storage::disk('public')->allFiles("exports/{$mailbox}") as $file) {
                        $zip->add(public_path("storage/{$file}"), $file);
                    }

                    return $zip;
                }
            }
        }
    }

    public function downloadAttachment(ProvidesArchiveFileSystem $archive_file_system, ArchiveUser $mailbox, string $message, int $attachment)
    {
        $mailbox = ArchiveUser::from_string($mailbox);
        $message = Crypt::decryptString($message);
        try {
            $parsed = $this->rendersMailMessage->parse($archive_file_system->fetchMessage($mailbox, $message));
            $parsed_attachment = $this->rendersMailAttachments->listAttachments($parsed);

            return response()->streamDownload(function () use ($parsed, $attachment) {
                echo $this->rendersMailAttachments->renderAttachment($parsed, $attachment);
            }, $parsed_attachment[$attachment]->filename);
        } catch (\Exception $e) {
            abort(500, 'failed to parse attachment');
        }
    }

    public function downloadMessage(ProvidesArchiveFileSystem $archive_file_system, ArchiveUser $mailbox, string $message)
    {
        $message = Crypt::decryptString($message);
        try {
            return response()->streamDownload(function () use ($archive_file_system, $mailbox, $message) {
                echo $archive_file_system->fetchMessage($mailbox, $message);
            },
                basename($message)
            );
        } catch (\Exception $e) {
            abort(500, 'failed to parse message');
        }
    }
}
