<?php

namespace EmailSender\Domain;

class To
{
    /**
     * @param array<int, array<string, string>> $to
     */
    public function __construct(private array $to = [])
    {
    }

    /**
     * @return array<int, array<string, string>> $to
     */
    public function getTo(): array
    {
        return $this->to;
    }
}
