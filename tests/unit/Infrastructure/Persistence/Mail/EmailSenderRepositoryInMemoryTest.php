<?php

namespace EmailSender\Tests\Infrastructure\Persistence\Mail;

use EmailSender\Domain\Model\Mail\Attachment;
use EmailSender\Domain\Model\Mail\Contact;
use EmailSender\Domain\Model\Mail\HtmlContent;
use EmailSender\Domain\Model\Mail\Mail;
use EmailSender\Domain\Model\Mail\MailId;
use EmailSender\Domain\Model\Mail\Recipient;
use EmailSender\Domain\Model\Mail\Sender;
use EmailSender\Domain\Model\Mail\Subject;
use EmailSender\Infrastructure\Persistence\Mail\EmailSenderRepositoryInMemory;
use PHPUnit\Framework\TestCase;

class EmailSenderRepositoryInMemoryTest extends EmailSenderRepositoryTestBase
{
    protected function setUp(): void
    {
        $this->repository = new EmailSenderRepositoryInMemory();
    }
}
