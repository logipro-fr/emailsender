<?php

namespace EmailSender\Tests;

use Brevo\Client\Model\SendSmtpEmailAttachment;
use PHPUnit\Framework\TestCase;
use EmailSender\EmailSender;
use EmailSender\RequestEmailSender;

class SenderMailTest extends TestCase
{
    public function testSendEmail(): void
    {
        $apiKey = 'apikey';

        $emailSender = new EmailSender($apiKey);

        $subject = 'Kakoo kakoo';
        $sender = ['name' => 'Mathis', 'email' => 'sender@example.com'];
        $recipients = [['name' => 'Recipient Name', 'email' => 'romain.malosse@logipro.com']];
        $htmlContent = "<html><head></head><body><a href='https://www.amazon.fr/'>Coucou lien</a></body></html>";

        $filePath = __DIR__.'/revue.pdf';
        $fileContent = file_get_contents($filePath);
        $fileBase64 = base64_encode($fileContent);

        $attachment = new SendSmtpEmailAttachment([
            'content' => $fileBase64,
            'name' => 'revue.pdf',
        ]);

        $emailSender->isAuthenticated("Mathis");
        $result = $emailSender->sendMail(new RequestEmailSender($subject, $sender, $recipients, $htmlContent, [$attachment]));

        if (method_exists($result, 'getMessageId')) {
            $messageId = $result->getMessageId();
            $this->assertNotEmpty($messageId, "L'ID du message ne doit pas être vide.");
            var_dump("L'email a été envoyé avec succès. ID du message : " . $messageId);
        }

        $this->assertIsObject($result);
    }
}
