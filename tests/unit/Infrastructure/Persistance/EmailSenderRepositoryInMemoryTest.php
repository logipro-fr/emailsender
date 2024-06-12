<?php

namespace EmailSender\Tests\Infrastructure\Persistance;

use EmailSender\Domain\Attachment;
use EmailSender\Domain\Contact;
use EmailSender\Domain\HtmlContent;
use EmailSender\Domain\Mail;
use EmailSender\Domain\Model\Mail\MailId;
use EmailSender\Domain\Recipient;
use EmailSender\Domain\Sender;
use EmailSender\Domain\Subject;
use EmailSender\Infrastructure\Persistance\EmailSenderRepositoryInMemory;
use PHPUnit\Framework\TestCase;

class EmailSenderRepositoryInMemoryTest extends TestCase
{
    private EmailSenderRepositoryInMemory $repository;

    protected function setUp(): void
    {
        $this->repository = new EmailSenderRepositoryInMemory();
    }

    public function testAddTrafficData(): void
    {
        $dataId = new MailId();
        $mailData = $this->mailDataForTests($dataId);

        $this->repository->add($mailData);
        $this->assertNotEmpty($this->repository->findById($dataId));
    }

    public function testFindByTrafficData(): void
    {
        $dataId1 = new MailId();
        $mailData1 = $this->mailDataForTests($dataId1);

        $dataId2 = new MailId();
        $mailData2 = $this->mailDataForTests($dataId2);

        $this->repository->add($mailData1);
        $this->repository->add($mailData2);

        $found1 = $this->repository->findById($dataId1);
        $found2 = $this->repository->findById($dataId2);

        $this->assertFalse($found1->getMailId()->equals($found2->getMailId()));
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
