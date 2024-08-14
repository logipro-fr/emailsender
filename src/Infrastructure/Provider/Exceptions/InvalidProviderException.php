<?php

namespace EmailSender\Infrastructure\Provider\Exceptions;

use EmailSender\Domain\Model\Mail\Exceptions\LoggedException;

class InvalidProviderException extends LoggedException {
    public const ERROR_CODE  = 422;
}