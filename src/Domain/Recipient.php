<?php

namespace EmailSender\Domain;

class Recipient
{
    /**
     * @param array<int, Contact> $recipient
     */
    public function __construct(private array $recipient)
    {
    }

    public function getRecipientName(int $rang): string
    {
        return $this->recipient[$rang]->getName();
    }

    public function getRecipientAddress(int $rang): string
    {
        return $this->recipient[$rang]->getAddress();
    }
}
