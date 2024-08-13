<?php

namespace EmailSender\Domain\Model\Mail;

use InvalidArgumentException;

class Subject
{
    public function __construct(private string $subject)
    {
        if (empty($this->subject)) {
            throw new InvalidArgumentException("Subject cannot be empty");
        }
    }

    public function getSubject(): string
    {

        return $this->subject;
    }

    public function __toString()
    {
        return $this->subject;
    }
}
