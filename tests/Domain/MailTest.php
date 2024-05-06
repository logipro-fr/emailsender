<?php

namespace EmailSender\Tests\Domain;

use EmailSender\Domain\Attachment;
use EmailSender\Domain\HtmlContent;
use EmailSender\Domain\Mail;
use EmailSender\Domain\Sender;
use EmailSender\Domain\Subject;
use EmailSender\Domain\To;
use PHPUnit\Framework\TestCase;

class MailTest extends TestCase
{
    public function testMail(): void
    {
        $mail = new Mail(
            new Subject('Test Email'),
            new Sender(['name' => 'Sender Name', 'email' => 'sender@example.com']),
            new To([['name' => 'Recipient Name', 'email' => 'recipient@example.com']]),
            new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            new Attachment([])
        );
        $this->assertEquals('Test Email', $mail->getMailSubject());
        $this->assertEquals(['name' => 'Sender Name', 'email' => 'sender@example.com'], $mail->getMailSender());
        $this->assertEquals([['name' => 'Recipient Name', 'email' => 'recipient@example.com']], $mail->getMailTo());
        $this->assertEquals('<html><body><h1>This is a test email</h1></body></html>', $mail->getMailHtmlContent());
        $this->assertEquals([], $mail->getMailAttachment());
    }
}
