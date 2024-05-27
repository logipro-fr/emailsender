<?php

namespace EmailSender\Application\Service\SendMail;

use EmailSender\Application\Service\SendMail\Exceptions\ErrorMailSenderException;

interface EmailApiInterface
{
    /**
     * @param array<string, mixed> $emailData
     * @return array<string, mixed>
     */
    public function sendEmail(array $emailData): array;
}
