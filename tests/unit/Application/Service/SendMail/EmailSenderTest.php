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
use InvalidArgumentException;
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
        $this->assertEquals('Pedro', $mail->getSender()->getSenderName());
        $this->assertEquals('pedro@gmail.com', $mail->getSender()->getSenderAddress());
        $this->assertEquals('Mathis', $mail->getRecipient()->getRecipients()[1]->getName());
        $this->assertEquals('Mathis@gmail.com',$mail->getRecipient()->getRecipients()[1]->getAddress());
        $this->assertEquals('Pedro', $mail->getRecipient()->getRecipients()[0]->getName());
        $this->assertEquals('pedro@gmail.com', $mail->getRecipient()->getRecipients()[0]->getAddress());
    }

    public function testMailFactoryWithCustomId(): void
    {
        $factory = new MailFactory();
        $mail = $factory->buildMailFromRequest($this->request, new MailId('test'));
        $this->assertEquals(new MailId('test'), $mail->getMailId());
    }

    public function testMailFactorySeparatorException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage("Error : Invalid seperator provided in the mail factory");

        $factory = new MailFactory();
        $mail = $factory->buildMailFromRequest($this->request, new MailId('test'), "");
    }
}
