<?php

namespace EmailSender\Infrastructure\Persistence\Mail\Exceptions;

use EmailSender\Domain\Model\Mail\Exceptions\LoggedException;

class MailNotFoundException extends LoggedException
{
    public const ERROR_CODE = 500;
}
