<?php

namespace EmailSender\Domain\Model\Mail;

use InvalidArgumentException;

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

    /**
     * @return array<int, Contact>
     */
    public function getRecipients(): array
    {
        return $this->recipient;
    }

    public function __toString()
    {
        $result = "";
        for ($i = 0; $i < count($this->recipient); $i++) {
            $result .= $this->recipient[$i]->getName() .", " . $this->recipient[$i]->getAddress();
        }
        return $result;
    }
}
