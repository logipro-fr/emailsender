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
        $mailData = new Mail(
            new Subject('Test Email Infra Repo'),
            new Sender(new Contact("Sender Name", "sender@example.com")),
            new Recipient([new Contact("morgan chemarin", "morgan.chemarin@logipro.com")]),
            new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            new Attachment([]),
            $dataId
        );

        $this->repository->add($mailData);
        $this->assertNotEmpty($this->repository->findById($dataId));
    }
}
