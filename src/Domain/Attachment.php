<?php

namespace EmailSender\Domain;

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
}
