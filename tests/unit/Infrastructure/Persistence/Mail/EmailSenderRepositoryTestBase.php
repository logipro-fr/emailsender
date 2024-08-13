<?php

namespace EmailSender\Tests\Infrastructure\Persistence\Mail;

use EmailSender\Domain\Model\Mail\Attachment;
use EmailSender\Domain\Model\Mail\Contact;
use EmailSender\Domain\Model\Mail\EmailSenderRepositoryInterface;
use EmailSender\Domain\Model\Mail\HtmlContent;
use EmailSender\Domain\Model\Mail\Mail;
use EmailSender\Domain\Model\Mail\MailId;
use EmailSender\Domain\Model\Mail\Recipient;
use EmailSender\Domain\Model\Mail\Sender;
use EmailSender\Domain\Model\Mail\Subject;
use EmailSender\Infrastructure\Persistence\Mail\Exceptions\MailNotFoundException;
use PHPUnit\Framework\TestCase;

abstract class EmailSenderRepositoryTestBase extends TestCase
{
    protected EmailSenderRepositoryInterface $repository;

    public function testFindById(): void
    {
        $mail1 = $this->mailDataForTests(new MailId("mail1"));
        $mail2 = $this->mailDataForTests(new MailId("mail2"));

        $this->repository->add($mail1);
        $found = $this->repository->findById(new MailId("mail1"));
        $this->repository->add($mail2);
        $found2 = $this->repository->findById(new MailId("mail2"));

        $this->assertInstanceOf(EmailSenderRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(Mail::class, $found);
        $this->assertEquals("mail1", $found->getMailId());
        $this->assertFalse($found->getMailId()->equals($found2->getMailId()));
    }

    public function testFindByIdException(): void
    {
        $this->expectException(MailNotFoundException::class);
        $this->expectExceptionMessage("Error can't find the mailId");
        $this->expectExceptionCode(MailNotFoundException::ERROR_CODE);

        $found = $this->repository->findById(new MailId("test"));
    }

    private function mailDataForTests(MailId $mailId): Mail
    {
        return new Mail(
            new Subject('Test Email Infra Repo'),
            new Sender(new Contact("Sender Name", "sender@example.com")),
            new Recipient([new Contact("Recipient Name", "recipient@example.com")]),
            new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            new Attachment([]),
            $mailId
        );
    }
}
