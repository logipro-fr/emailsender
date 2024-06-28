<?php

namespace EmailSender\Application\Service\SendMail;

class SendMailResponse
{
    public function __construct(
        public readonly string $mailId,
    ) {
    }
}
