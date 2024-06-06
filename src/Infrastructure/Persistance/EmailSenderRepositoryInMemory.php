<?php

namespace EmailSender\Infrastructure\Persistance;

use EmailSender\Domain\EmailSenderRepositoryInterface;
use EmailSender\Domain\Mail;
use EmailSender\Domain\Model\Mail\MailId;

class EmailSenderRepositoryInMemory implements EmailSenderRepositoryInterface
{
    /** @var array<Mail> */
    private array $arrayMail = [];

    public function add(Mail $mailData): void
    {
        $this->arrayMail[$mailData->getMailId()->__toString()] = $mailData;
    }

    public function findById(MailId $id): Mail
    {
        return $this->arrayMail[$id->__toString()];
    }
}
