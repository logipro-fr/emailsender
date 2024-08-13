<?php

namespace EmailSender\Infrastructure\Persistence\Mail;

use EmailSender\Domain\Model\Mail\EmailSenderRepositoryInterface;
use EmailSender\Domain\Model\Mail\Mail;
use EmailSender\Domain\Model\Mail\MailId;
use EmailSender\Infrastructure\Persistence\Mail\Exceptions\MailNotFoundException;

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
        if (!isset($this->arrayMail[$id->__toString()])) {
            throw new MailNotFoundException(
                sprintf(
                    "Error can't find the mailId %s",
                    $id->__toString()
                ),
                MailNotFoundException::ERROR_CODE
            );
        }
        return $this->arrayMail[$id->__toString()];
    }
}
