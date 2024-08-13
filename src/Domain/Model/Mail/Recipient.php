<?php

namespace EmailSender\Domain\Model\Mail;

use InvalidArgumentException;
use OutOfBoundsException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class Recipient
{
    /**
     * @param array<int, Contact> $recipient
     */
    public function __construct(private array $recipient)
    {
        if (empty($recipient)) {
            throw new InvalidArgumentException("Recipients cannot be empty");
        }
    }

    public function getRecipientName(int $rank): string
    {
        if (!isset($this->recipient[$rank])) {
            throw new OutOfBoundsException("This recipient rank doesn't exist");
        }

        return $this->recipient[$rank]->getName();
    }

    public function getRecipientAddress(int $rank): string
    {
        if (!isset($this->recipient[$rank])) {
            throw new OutOfBoundsException("This recipient rank doesn't exist");
        }

        return $this->recipient[$rank]->getAddress();
    }

    public function __toString()
    {
        $result = "";
        for ($i = 0; $i < count($this->recipient); $i++) {
            $result .= $this->getRecipientName($i) . " " . $this->getRecipientAddress($i);
        }
        return $result;
    }
}
