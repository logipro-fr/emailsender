<?php

namespace EmailSender\Application\Service\SendMail;

interface EmailApiInterface
{
    public function sendMail(RequestEmailSender $request): bool;
}
