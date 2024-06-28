<?php

namespace EmailSender\Infrastructure\Provider\Brevo;

use EmailSender\Application\Service\SendMail\EmailApiInterface;
use EmailSender\Application\Service\SendMail\Exceptions\ErrorMailSenderException;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\CreateSmtpEmail;
use Brevo\Client\Model\SendSmtpEmail;
use EmailSender\Application\Service\SendMail\SendMail;
use EmailSender\Application\Service\SendMail\MailFactory;
use EmailSender\Application\Service\SendMail\SendMailRequest;
use EmailSender\Domain\Model\Mail\MailId;
use EmailSender\Infrastructure\Persistance\EmailSenderRepositoryInMemory;
use GuzzleHttp\Client;
use Exception;
use GuzzleHttp\ClientInterface;

class BrevoSender implements EmailApiInterface
{
    private const API_KEY = 'api-key';
    private const HTTP_ERROR_500 = 500;
    private const ERROR_SENDING = "Erreur lors de l'envoi du mail";

    private TransactionalEmailsApi $emailApi;

    public function __construct(string $apiKey, ClientInterface $client = new Client())
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey(self::API_KEY, $apiKey);
        $this->emailApi = new TransactionalEmailsApi($client, $config);
    }

    public function sendMail(SendMailRequest $request): bool
    {
        try {
            $mail = (new MailFactory())->buildMailFromRequest($request);

            $sendSmtpEmail = new SendSmtpEmail([
                'subject' => $mail->getSubject(),
                'sender' => $mail->getSenderData(),
                'to' => $mail->getRecipientData(0),
                'htmlContent' => $mail->getHtmlContent(),
                'attachment' => $mail->getAttachment(),
            ]);

            $this->emailApi->sendTransacEmail($sendSmtpEmail);
            return true;
        } catch (Exception $e) {
            throw new ErrorMailSenderException(self::ERROR_SENDING, self::HTTP_ERROR_500, $e);
        }
    }
}
