<?php

namespace EmailSender\Application\Service\SendMail;

use EmailSender\Application\Service\SendMail\Exceptions\ErrorMailSenderException;
use EmailSender\Domain\Mail;
use EmailSender\Domain\Model\Mail\MailId;

interface EmailApiInterface
{
    public function sendEmail(Mail $email): MailId;
}
