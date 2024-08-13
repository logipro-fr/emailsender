<?php

namespace EmailSender\Application\Service\SendMail;

use EmailSender\Domain\Model\Mail\Mail;

interface EmailApiInterface
{
    public function sendMail(Mail $emailToSend): bool;
}
