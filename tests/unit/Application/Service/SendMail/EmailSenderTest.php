<?php

namespace EmailSender\Tests;

use PHPUnit\Framework\TestCase;
use EmailSender\Application\Service\SendMail\EmailApiInterface;
use EmailSender\Application\Service\SendMail\EmailSender;
use EmailSender\Application\Service\SendMail\Exceptions\ErrorAuthException;
use EmailSender\Application\Service\SendMail\Exceptions\ErrorMailSenderException;
use EmailSender\Application\Service\SendMail\RequestEmailSender;
use EmailSender\Domain\Attachment;
use EmailSender\Domain\Contact;
use EmailSender\Domain\HtmlContent;
use EmailSender\Domain\Mail;
use EmailSender\Domain\Recipient;
use EmailSender\Domain\Sender;
use EmailSender\Domain\Subject;

class EmailSenderTest extends TestCase
{
    private const CURRENT_USER = "Mathis";

    public function testSendTransactionalEmailSuccess(): void
    {
        $apiMock = $this->createMock(EmailApiInterface::class);
        $apiMock->method('sendEmail')->willReturn(['messageId' => '1234']);
        $instanceEmailSender = new EmailSender($apiMock);
        $mailData = $this->mailDataForTests();
        $instanceEmailSender->isAuthenticated(self::CURRENT_USER);
        $response = $instanceEmailSender->sendMail($mailData);
        $this->assertIsArray($response);
        $this->assertEquals('1234', $response['messageId']);
    }

    public function testSendTransactionalEmailAuthFailure(): void
    {
        $apiMock = $this->createMock(EmailApiInterface::class);
        $instanceEmailSender = new EmailSender($apiMock);
        $mailData = $this->mailDataForTests();
        $this->expectException(ErrorAuthException::class);
        $instanceEmailSender->sendMail($mailData);
    }

    public function testSendTransactionalEmailSendingFailure(): void
    {
        $apiMock = $this->createMock(EmailApiInterface::class);
        $apiMock->method('sendEmail')->willThrowException(new ErrorMailSenderException());
        $instanceEmailSender = new EmailSender($apiMock);
        $mailData = $this->mailDataForTests();
        $this->expectException(ErrorMailSenderException::class);
        $instanceEmailSender->isAuthenticated(self::CURRENT_USER);
        $instanceEmailSender->sendMail($mailData);
    }

    public function testContentEmailData(): void
    {
        $apiMock = $this->createMock(EmailApiInterface::class);
        $instanceEmailSender = new EmailSender($apiMock);

        $emailDataForTesting = [
            'subject' => $this->mailDataForTests()->mail->getSubject(),
            'sender' => $this->mailDataForTests()->mail->getSenderData(),
            'to' => $this->mailDataForTests()->mail->getRecipientData(),
            'htmlContent' => $this->mailDataForTests()->mail->getHtmlContent(),
            'attachment' => $this->mailDataForTests()->mail->getAttachment(),
        ];

        $instanceEmailSender->isAuthenticated(self::CURRENT_USER);
        $emailData = $instanceEmailSender->contentEmailData($this->mailDataForTests());
        $this->assertEquals($emailData, $emailDataForTesting);
    }

    public function mailDataForTests(): RequestEmailSender
    {
        $mail = new Mail(
            new Subject('Test Email Infra'),
            new Sender(new Contact("Sender Name", "sender@example.com")),
            new Recipient([new Contact("Mathis Tallaron", "mathis.tallaron@logipro.com")]),
            new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            new Attachment([])
        );

        return new RequestEmailSender($mail);
    }
}
















