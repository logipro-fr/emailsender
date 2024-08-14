<?php

namespace EmailSender\Tests\Domain\Mail;

use EmailSender\Domain\Model\Mail\Attachment;
use EmailSender\Domain\Model\Mail\Contact;
use EmailSender\Domain\Model\Mail\HtmlContent;
use EmailSender\Domain\Model\Mail\Mail;
use EmailSender\Domain\Model\Mail\MailId;
use EmailSender\Domain\Model\Mail\Recipient;
use EmailSender\Domain\Model\Mail\Sender;
use EmailSender\Domain\Model\Mail\Subject;
use PHPUnit\Framework\TestCase;
use Safe\DateTimeImmutable;

class MailTest extends TestCase
{
    public function testMail(): void
    {
        $mail = new Mail(
            $subject = new Subject('Test Email'),
            $sender = new Sender(new Contact("Sender Name", "sender@example.com")),
            $recipient = new Recipient([new Contact("Recipient Name", "recipient@example.com")]),
            $htmlContent = new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            $attachment = new Attachment([]),
        );
        $this->assertEquals($subject, $mail->getSubject());
        $this->assertEquals($sender, $mail->getSender());
        $this->assertEquals($recipient, $mail->getRecipient());
        $this->assertEquals($htmlContent, $mail->getHtmlContent());
        $this->assertStringStartsWith("mai_", $mail->getMailId());
        $this->assertEquals($attachment, $mail->getAttachment());
    }

    public function testMailWithCustomId(): void
    {
        $mail = new Mail(
            new Subject('Test Email'),
            new Sender(new Contact("Sender Name", "sender@example.com")),
            new Recipient([new Contact("Recipient Name", "recipient@example.com")]),
            new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            new Attachment([]),
            new MailId("test")
        );

        $this->assertEquals("test", $mail->getMailId());
    }

    public function testMailCreatedAt(): void
    {
        $date = new DateTimeImmutable();
        $mail = new Mail(
            new Subject('Test Email'),
            new Sender(new Contact("Sender Name", "sender@example.com")),
            new Recipient([new Contact("Recipient Name", "recipient@example.com")]),
            new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            new Attachment([]),
            new MailId("test"),
            $date
        );

        $this->assertEquals($date, $mail->getCreatedAt());
    }
}
