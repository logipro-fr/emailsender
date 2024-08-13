<?php

namespace EmailSender\Domain\Model\Mail;

use ArgumentCountError;

class Sender
{
    /**
     * @param Contact $sender
     */
    public function __construct(private Contact $sender)
    {
    }

    /**
     * @return string $sender
     */
    public function getSenderName(): string
    {
        return $this->sender->getName();
    }

    /**
     * @return string $sender
     */
    public function getSenderAddress(): string
    {
        return $this->sender->getAddress();
    }

    public function __toString()
    {

        return $this->sender->getName() .  " " . $this->sender->getAddress();
    }
}
