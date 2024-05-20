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
        $this->assertEquals('Test Email', $mail->getSubject());
        $this->assertEquals("Sender Name", $mail->getSenderName());
        $this->assertEquals("sender@example.com", $mail->getSenderAddress());
        $this->assertEquals("Recipient Name", $mail->getRecipientName());
        $this->assertEquals("recipient@example.com", $mail->getRecipientAddress());
        $this->assertEquals('<html><body><h1>This is a test email</h1></body></html>', $mail->getHtmlContent());
        $this->assertEquals([], $mail->getAttachment());
    }
}
