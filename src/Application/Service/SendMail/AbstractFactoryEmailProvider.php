<?php

namespace EmailSender\Application\Service\SendMail;

abstract class AbstractFactoryEmailProvider
{
    abstract public function buildProvider(string $provider): EmailApiInterface;
}
