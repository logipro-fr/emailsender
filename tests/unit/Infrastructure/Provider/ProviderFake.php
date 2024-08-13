<?php

namespace EmailSender\Tests\Infrastructure\Provider;

use EmailSender\Application\Service\SendMail\EmailApiInterface;
use EmailSender\Domain\Model\Mail\Mail;

class ProviderFake implements EmailApiInterface
{
    public function sendMail(Mail $emailToSend): bool
    {
        return true;
    }
}
