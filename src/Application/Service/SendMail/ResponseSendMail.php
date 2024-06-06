<?php

namespace EmailSender\Application\Service\SendMail;

class ResponseSendMail
{
    public function __construct(
        public readonly string $mailId,
    ) {
    }
}
