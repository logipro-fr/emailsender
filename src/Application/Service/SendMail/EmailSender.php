<?php

namespace EmailSender\Application\Service\SendMail;

use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use Brevo\Client\Configuration;
use Brevo\Client\Model\CreateSmtpEmail;
use EmailSender\Application\Service\SendMail\Exceptions\ErrorAuthException;
use EmailSender\Application\Service\SendMail\Exceptions\ErrorMailSenderException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class EmailSender
{
    private TransactionalEmailsApi $apiInstance;
    private string $user;
    private const HTTP_ERROR_401 = 401;
    private const HTTP_ERROR_500 = 500;
    private const API_KEY = 'api-key';
    private const ERROR_SENDING = "Erreur lors de l'envoi du mail";
    private const ERROR_AUTH = "Utilisateur non authentifié";

    public function __construct(string $apiKey, ClientInterface $client = new Client())
    {
        $config = Configuration::getDefaultConfiguration()
            ->setApiKey(self::API_KEY, $apiKey);

        $this->apiInstance = new TransactionalEmailsApi($client, $config);
    }

    public function isAuthenticated(string $user): ResponseIsAuth
    {
        $this->user = $user;
        return new ResponseIsAuth($this->user);
    }

    public function sendMail(RequestEmailSender $request): CreateSmtpEmail
    {
        if (isset($this->user)) {
            $sendSmtpEmail = $this->contentSmtpEmail($request);

            try {
                return $this->apiInstance->sendTransacEmail($sendSmtpEmail);
            } catch (Exception $e) {
                throw new ErrorMailSenderException(self::ERROR_SENDING, self::HTTP_ERROR_500, $e);
            }
        }

        throw new ErrorAuthException(self::ERROR_AUTH, self::HTTP_ERROR_401);
    }

    public function contentSmtpEmail(RequestEmailSender $request): SendSmtpEmail
    {
        return new SendSmtpEmail([
            'subject' => $request->mail->getMailSubject(),
            'sender' => $request->mail->getMailSenderData(),
            'to' => $request->mail->getMailRecipientData(),
            'htmlContent' => $request->mail->getMailHtmlContent(),
            'attachment' => $request->mail->getMailAttachment(),
        ]);
    }
}
