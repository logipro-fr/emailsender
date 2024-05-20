<?php

namespace EmailSender\Tests;

use Brevo\Client\Model\SendSmtpEmailAttachment;
use PHPUnit\Framework\TestCase;
use EmailSender\Application\Service\SendMail\EmailSender;
use EmailSender\Application\Service\SendMail\RequestEmailSender;
use EmailSender\Domain\Attachment;
use EmailSender\Domain\HtmlContent;
use EmailSender\Domain\Mail;
use EmailSender\Domain\Recipient;
use EmailSender\Domain\Sender;
use EmailSender\Domain\Subject;

class SenderMailTest extends TestCase
{
    public function testSendEmail(): void
    {
        $apiKey = 'apikey';

        $emailSender = new EmailSender($apiKey);


        $filePath = __DIR__.'/revue.pdf';
        $fileContent = file_get_contents($filePath);
        $fileBase64 = base64_encode($fileContent);
        $attachment = new SendSmtpEmailAttachment([
            'content' => $fileBase64,
            'name' => 'revue.pdf',
        ]);

        $mail = new Mail(
            new Subject('Test Email'),
            new Sender(['name' => 'Ton admirateur secret', 'email' => 'sender@example.com']),
            new Recipient([['name' => 'Recipient Name', 'email' => 'morgan.chemarin@logipro.com']]),
            new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            new Attachment([$attachment])
        );


        $emailSender->isAuthenticated("Mathis");
        $result = $emailSender->sendMail(new RequestEmailSender($mail));

        if (method_exists($result, 'getMessageId')) {
            $messageId = $result->getMessageId();
            $this->assertNotEmpty($messageId, "L'ID du message ne doit pas être vide.");
            var_dump("L'email a été envoyé avec succès. ID du message : " . $messageId);
        }

        $this->assertIsObject($result);
    }
}
