<?php

namespace EmailSender\Tests\Domain;

use EmailSender\Domain\Attachment;
use EmailSender\Domain\Contact;
use EmailSender\Domain\HtmlContent;
use EmailSender\Domain\Mail;
use EmailSender\Domain\Recipient;
use EmailSender\Domain\Sender;
use EmailSender\Domain\Subject;
use PHPUnit\Framework\TestCase;

class MailTest extends TestCase
{
    public function testMail(): void
    {
        $mail = new Mail(
            new Subject('Test Email'),
            new Sender(new Contact("Sender Name", "sender@example.com")),
            new Recipient([new Contact("Recipient Name", "recipient@example.com")]),
            new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            new Attachment([])
        );
        $this->assertEquals('Test Email', $mail->getMailSubject());
        $this->assertEquals("Sender Name", $mail->getMailSenderName());
        $this->assertEquals("sender@example.com", $mail->getMailSenderAddress());
        $this->assertEquals("Recipient Name", $mail->getMailRecipientName());
        $this->assertEquals("recipient@example.com", $mail->getMailRecipientAddress());
        $this->assertEquals('<html><body><h1>This is a test email</h1></body></html>', $mail->getMailHtmlContent());
        $this->assertEquals([], $mail->getMailAttachment());
    }
}
