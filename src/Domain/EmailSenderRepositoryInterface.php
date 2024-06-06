<?php

namespace EmailSender\Domain;

use EmailSender\Domain\Model\Mail\MailId;

interface EmailSenderRepositoryInterface
{
    public function add(Mail $mailData): void;
    public function findById(MailId $id): Mail;
}
