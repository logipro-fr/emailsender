<?php

namespace EmailSender\Domain;

use PhpParser\Node\Expr\Cast\Object_;

class Sender
{
    /**
     * @param array<string, string> $sender
     */
    public function __construct(private array $sender)
    {
    }

    /**
     * @return array<string, string> $sender
     */
    public function getSender(): array
    {
        return $this->sender;
    }
}
