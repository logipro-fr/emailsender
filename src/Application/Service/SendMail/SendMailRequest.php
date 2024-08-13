<?php

namespace EmailSender\Application\Service\SendMail;

use EmailSender\Domain\Model\Mail\Mail;

class SendMailRequest
{
    public function __construct(
        public readonly string $sender,
        /** @var array<string> */
        public readonly array $recipient,
        public readonly string $subject,
        public readonly string $content,
        public readonly string $provider
    ) {
    }
}
