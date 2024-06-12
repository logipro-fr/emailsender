<?php

namespace EmailSender\Tests;

use PHPUnit\Framework\TestCase;
use EmailSender\Application\Service\SendMail\EmailApiInterface;
use EmailSender\Application\Service\SendMail\EmailSender;
use EmailSender\Application\Service\SendMail\Exceptions\ErrorAuthException;
use EmailSender\Application\Service\SendMail\MailFactory;
use EmailSender\Application\Service\SendMail\RequestEmailSender;
use EmailSender\Application\Service\SendMail\ResponseSendMail;
use EmailSender\Domain\Attachment;
use EmailSender\Domain\Contact;
use EmailSender\Domain\HtmlContent;
use EmailSender\Domain\Mail;
use EmailSender\Domain\Model\Mail\MailId;
use EmailSender\Domain\Recipient;
use EmailSender\Domain\Sender;
use EmailSender\Domain\Subject;
use EmailSender\Infrastructure\Persistance\EmailSenderRepositoryInMemory;

class EmailSenderTest extends TestCase
{
    private RequestEmailSender $request;

    public function setUp(): void
    {
        $this->request = new RequestEmailSender(
            "Pedro, pedro@gmail.com",
            ["Pedro, pedro@gmail.com", "Mathis, Mathis@gmail.com"],
            "Email test",
            "<html><body><h1>This is a test email</h1></body></html>",
        );
    }

    public function testSendTransactionalEmailSuccess(): void
    {
        $apiMock = $this->createMock(EmailApiInterface::class);
        $mailId = new MailId("test");
        $apiMock->method('sendMail')->willReturn(true);
        $repository = new EmailSenderRepositoryInMemory();
        $service = new EmailSender($repository, $apiMock, "test");

        $service->execute($this->request);
        $response = $service->getResponse();

        $this->assertInstanceOf(ResponseSendMail::class, $response);
        $this->assertEquals('test', $response->mailId);


        $this->assertNotEmpty($repository->findById($mailId));
    }

    public function testMailFactory(): void
    {
        $factory = new MailFactory();
        $mail = $factory->buildMailFromRequest($this->request);
        $this->assertInstanceOf(Mail::class, $mail);
        $this->assertEquals('Pedro', $mail->getSenderName());
        $this->assertEquals('pedro@gmail.com', $mail->getSenderAddress());
        $this->assertEquals('Mathis', $mail->getRecipientName(1));
        $this->assertEquals('Mathis@gmail.com', $mail->getRecipientAddress(1));
        $this->assertEquals('Pedro', $mail->getRecipientName(0));
        $this->assertEquals('pedro@gmail.com', $mail->getRecipientAddress(0));
    }

    public function testMailFactoryWithCustomId(): void
    {
        $factory = new MailFactory();
        $mail = $factory->buildMailFromRequest($this->request, new MailId('test'));
        $this->assertEquals(new MailId('test'), $mail->getMailId());
    }

    public function mailDataForTests(): Mail
    {
        return new Mail(
            new Subject('Test Email Infra'),
            new Sender(new Contact("Sender Name", "sender@example.com")),
            new Recipient([new Contact("Mathis Tallaron", "mathis.tallaron@logipro.com")]),
            new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            new Attachment([])
        );
    }
}
