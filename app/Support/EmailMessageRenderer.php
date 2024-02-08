<?php

namespace App\Support;

use App\Contracts\RendersMailAttachments;
use App\Contracts\RendersMailMessage;
use App\Data\ArchivedMessageAttachmentData;
use eXorus\PhpMimeMailParser\Parser;
use Spatie\LaravelData\DataCollection;

class EmailMessageRenderer implements RendersMailAttachments, RendersMailMessage
{
    public function parse(string $raw_mime_content): Parser
    {
        $parser = new Parser();
        $parser->setText($raw_mime_content);

        return $parser;
    }

    public function render(Parser $parser): string
    {
        $content_type = $parser->getHeader('content-type');
        if (empty($content_type) || $content_type === 'text/plain') {
            return $parser->getMessageBody();
        } else {
            return $parser->getMessageBody('html', true);
        }
    }

    public function renderContent(string $raw_mime_content): string
    {
        $parser = $this->parse($raw_mime_content);

        return $this->render($parser);
    }

    /**
     * @return array<ArchivedMessageAttachmentData>|DataCollection<ArchivedMessageAttachmentData>
     */
    public function listAttachments(Parser $parser): array|DataCollection
    {
        $results = [];
        foreach ($parser->getAttachments() as $i => $attachment) {
            $results[] = new ArchivedMessageAttachmentData(
                id: $i,
                filename: $attachment->getFilename(),
                contentType: $attachment->getContentType(),
                contentDisposition: $attachment->getContentDisposition(),
                contentId: $attachment->getContentID()
            );
        }

        return ArchivedMessageAttachmentData::collection($results);
    }

    public function renderAttachment(Parser $parser, int $id): string
    {
        $attachments = $parser->getAttachments();

        return $attachments[$id]->getContent();
    }
}
