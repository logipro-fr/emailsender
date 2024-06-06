<?php

namespace EmailSender\Application\Service\SendMail;

class ResponseIsAuth
{
    public function __construct(public readonly string $user)
    {
    }
}
