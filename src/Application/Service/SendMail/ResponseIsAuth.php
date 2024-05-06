<?php

namespace EmailSender\Application\Service\SendMail;

class ResponseIsAuth implements ResponseInterface
{
    public function __construct(public readonly string $user)
    {
    }
}
