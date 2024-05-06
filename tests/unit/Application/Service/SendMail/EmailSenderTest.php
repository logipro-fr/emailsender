<?php

namespace EmailSender\Tests;

use PHPUnit\Framework\TestCase;
use Brevo\Client\Model\SendSmtpEmail;
use Brevo\Client\Model\CreateSmtpEmail;
use EmailSender\Application\Service\SendMail\EmailSender;
use EmailSender\Application\Service\SendMail\Exceptions\ErrorAuthException;
use EmailSender\Application\Service\SendMail\Exceptions\ErrorMailSenderException;
use EmailSender\Application\Service\SendMail\RequestEmailSender;
use EmailSender\Domain\Attachment;
use EmailSender\Domain\HtmlContent;
use EmailSender\Domain\Mail;
use EmailSender\Domain\Sender;
use EmailSender\Domain\Subject;
use EmailSender\Domain\To;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class EmailSenderTest extends TestCase
{
    private const API_KEY = 'api-key';
    private const CURRENT_USER = "Mathis";

    public function testSendTransactionalEmailSuccess(): void
    {
        $emailApiSuccess = new EmailSender(self::API_KEY, $this->createMockGuzzle(200));
        $mailData = $this->mailDataForTests();
        $emailApiSuccess->isAuthenticated(self::CURRENT_USER);
        $response = $emailApiSuccess->sendMail($mailData);
        $this->assertInstanceOf(CreateSmtpEmail::class, $response);
        $this->assertEquals('1234', $response->getMessageId());
    }


    public function testSendTransactionalEmailAuthFailure(): void
    {
        $emailApiFailure = new EmailSender(self::API_KEY, $this->createMockGuzzle(401));
        $mailData = $this->mailDataForTests();
        $this->expectException(ErrorAuthException::class);
        $emailApiFailure->sendMail($mailData);
    }


    public function testSendTransactionalEmailSendingFailure(): void
    {
        $emailApiFailure = new EmailSender(self::API_KEY, $this->createMockGuzzle(500));
        $mailData = $this->mailDataForTests();
        $this->expectException(ErrorMailSenderException::class);
        $emailApiFailure->isAuthenticated(self::CURRENT_USER);
        $emailApiFailure->sendMail($mailData);
    }


    public function testContentSmtpMail(): void
    {
        $emailApiSmtpContent = new EmailSender(self::API_KEY, $this->createMockGuzzle(200));

        $smtpMailForTesting = new SendSmtpEmail([
            'subject' => $this->mailDataForTests()->mail->getMailSubject(),
            'sender' => $this->mailDataForTests()->mail->getMailSender(),
            'to' => $this->mailDataForTests()->mail->getMailTo(),
            'htmlContent' => $this->mailDataForTests()->mail->getMailHtmlContent(),
            'attachment' => $this->mailDataForTests()->mail->getMailAttachment(),
        ]);

        $emailApiSmtpContent->isAuthenticated(self::CURRENT_USER);
        $smtpContent = $emailApiSmtpContent->contentSmtpEmail($this->mailDataForTests());
        $this->assertEquals($smtpContent, $smtpMailForTesting);
    }


    private function createMockGuzzle(int $statusCode): Client
    {
        $mock = new MockHandler([
            new Response($statusCode, [], strval(json_encode(['messageId' => '1234']))),
        ]);
        $handlerStack = HandlerStack::create($mock);
        return new Client(['handler' => $handlerStack]);
    }


    public function mailDataForTests(): RequestEmailSender
    {
        $mail = new Mail(
            new Subject('Test Email'),
            new Sender(['name' => 'Sender Name', 'email' => 'sender@example.com']),
            new To([['name' => 'Recipient Name', 'email' => 'recipient@example.com']]),
            new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            new Attachment([])
        );

        return new RequestEmailSender($mail);
    }
}


    /*public function testIsAuthenticated(): void
    {
        $emailSenderMock = new EmailSender("ApiKeyFake");
        $result = $emailSenderMock->isAuthenticated("Mathis");

        $this->assertEquals("Mathis", $result->user);
    }*/
