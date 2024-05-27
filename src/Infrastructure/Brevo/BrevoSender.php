<?php

namespace EmailSender\Infrastructure\Brevo;

use EmailSender\Application\Service\SendMail\EmailApiInterface;
use EmailSender\Application\Service\SendMail\Exceptions\ErrorMailSenderException;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\CreateSmtpEmail;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;
use Exception;
use GuzzleHttp\ClientInterface;

class BrevoSender implements EmailApiInterface
{
    private const API_KEY = 'api-key';
    private const HTTP_ERROR_500 = 500;

    private TransactionalEmailsApi $apiInstance;

    public function __construct(string $apiKey, ClientInterface $client = new Client())
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey(self::API_KEY, $apiKey);
        $this->apiInstance = new TransactionalEmailsApi($client, $config);
    }

    public function sendEmail(array $emailData): array
    {
        $sendSmtpEmail = new SendSmtpEmail($emailData);

        try {
            $response = $this->apiInstance->sendTransacEmail($sendSmtpEmail);
            return ['messageId' => $response->getMessageId()];
        } catch (Exception $e) {
            throw new ErrorMailSenderException("Erreur lors de l'envoi du mail", self::HTTP_ERROR_500, $e);
        }
    }
}
