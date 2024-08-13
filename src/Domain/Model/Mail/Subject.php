<?php

namespace EmailSender\Domain\Model\Mail;

use InvalidArgumentException;

class Subject
{
    public function __construct(private string $subject)
    {
    }

    public function getSubject(): string
    {
        if (empty($this->subject)) {
            throw new InvalidArgumentException("Subject cannot be empty");
        }
        return $this->subject;
    }

    public function __toString()
    {
        return $this->subject;
    }
}
