<?php

namespace EmailSender\Domain;

class Subject
{
    public function __construct(private string $subject = "")
    {
    }

    public function getSubject(): string
    {
        return $this->subject;
    }
}
