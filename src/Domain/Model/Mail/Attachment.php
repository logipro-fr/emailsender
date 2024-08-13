<?php

namespace EmailSender\Domain\Model\Mail;

class Attachment
{
    /**
     * @param array<string, Attachment> $attachment
     */
    public function __construct(private array $attachment = [])
    {
    }

    /**
     * @return array<string, Attachment> $attachment
     */
    public function getAttachment(): array
    {
        return $this->attachment;
    }

    public function __toString()
    {
        return 'Attachments:';
    }
}
