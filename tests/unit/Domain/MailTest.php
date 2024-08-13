<?php

namespace EmailSender\Tests\Domain;

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
            new Subject('Test Email'),
            new Sender(new Contact("Sender Name", "sender@example.com")),
            new Recipient([new Contact("Recipient Name", "recipient@example.com")]),
            new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            new Attachment([]),
        );
        $this->assertEquals('Test Email', $mail->getSubject());
        $this->assertEquals("Sender Name", $mail->getSenderName());
        $this->assertEquals("sender@example.com", $mail->getSenderAddress());
        $this->assertEquals("Recipient Name", $mail->getRecipientName(0));
        $this->assertEquals("recipient@example.com", $mail->getRecipientAddress(0));
        $this->assertEquals('<html><body><h1>This is a test email</h1></body></html>', $mail->getHtmlContent());
        $this->assertStringStartsWith("mai_", $mail->getMailId());
        $this->assertEquals([], $mail->getAttachment());
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

    public function testGetSenderDataReturnsCorrectData(): void
    {
        $sender = new Sender(new Contact("Sender Name", "sender@example.com"));
        $recipient = new Recipient([
            new Contact('Jane Smith', 'jane.smith@example.com'),
            new Contact('Alice Johnson', 'alice.johnson@example.com')
        ]);
        $mailId = new MailId();
        $mail = new Mail(
            new Subject('Test Email'),
            $sender,
            $recipient,
            new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            new Attachment([]),
            $mailId
        );

        $senderData = $mail->getSenderData();

        $this->assertSame(['name' => 'Sender Name', 'email' => 'sender@example.com'], $senderData);
    }

    public function testGetRecipientDataReturnsCorrectData(): void
    {
        $sender = new Sender(new Contact('John Doe', 'john.doe@example.com'));
        $recipient = new Recipient([
            new Contact('Jane Smith', 'jane.smith@example.com'),
            new Contact('Alice Johnson', 'alice.johnson@example.com')
        ]);
        $mailId = new MailId();
        $mail = new Mail(
            new Subject('Test Email'),
            $sender,
            $recipient,
            new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            new Attachment([]),
            $mailId
        );

        $recipientData = $mail->getRecipientData(1);

        $this->assertSame([ 'email' => 'alice.johnson@example.com', 'name' => 'Alice Johnson'], $recipientData);
    }
}
