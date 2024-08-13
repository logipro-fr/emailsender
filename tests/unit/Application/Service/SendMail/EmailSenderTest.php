<?php

namespace EmailSender\Tests;

use PHPUnit\Framework\TestCase;
use EmailSender\Application\Service\SendMail\MailFactory;
use EmailSender\Application\Service\SendMail\SendMail;
use EmailSender\Application\Service\SendMail\SendMailRequest;
use EmailSender\Domain\Model\Mail\Mail;
use EmailSender\Domain\Model\Mail\MailId;
use EmailSender\Infrastructure\Persistence\Mail\EmailSenderRepositoryInMemory;
use EmailSender\Infrastructure\Provider\FactoryEmailProvider;
use Symfony\Component\HttpClient\MockHttpClient;

class EmailSenderTest extends TestCase
{
    private SendMailRequest $request;

    protected function setUp(): void
    {
        $this->request = new SendMailRequest(
            "Pedro, pedro@gmail.com",
            ["Pedro, pedro@gmail.com", "Mathis, Mathis@gmail.com"],
            "Email test",
            "<html><body><h1>This is a test email</h1></body></html>",
            "testProvider"
        );
    }

    public function testSendMail(): void
    {
        $repository = new EmailSenderRepositoryInMemory();
        $service = new SendMail($repository, new FactoryEmailProvider(new MockHttpClient()));
        $service->execute($this->request);
        $response = $service->getResponse();
        $this->assertStringStartsWith("mai_", $response->mailId);
    }

    public function testSendMailWithCustomId(): void
    {
        $repository = new EmailSenderRepositoryInMemory();
        $service = new SendMail($repository, new FactoryEmailProvider(new MockHttpClient()), "IdTest");
        $service->execute($this->request);
        $response = $service->getResponse();
        $this->assertEquals(($repository->findById(new MailId("IdTest")))->getMailId(), $response->mailId);
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
}
