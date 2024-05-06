<?php

namespace EmailSender\Application\Service\SendMail;

use EmailSender\Domain\Mail;

class RequestEmailSender implements RequestInterface
{
    public function __construct(
        public readonly Mail $mail
    ) {
    }
}
