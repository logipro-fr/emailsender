<?php

namespace EmailSender\Application\Service\SendMail;

use EmailSender\Application\Service\SendMail\Exceptions\ErrorAuthException;
use EmailSender\Application\Service\SendMail\Exceptions\ErrorMailSenderException;


class EmailSender
{
    private EmailApiInterface $emailApi;
    private string $user;
    private const HTTP_ERROR_401 = 401;
    private const HTTP_ERROR_500 = 500;
    private const ERROR_SENDING = "Erreur lors de l'envoi du mail";
    private const ERROR_AUTH = "Utilisateur non authentifié";

    public function __construct(EmailApiInterface $emailApi)
    {
        $this->emailApi = $emailApi;
    }

    public function isAuthenticated(string $user): ResponseIsAuth
    {
        $this->user = $user;
        return new ResponseIsAuth($this->user);
    }

    /**
     * @return array<string, mixed>
     */
    public function sendMail(RequestEmailSender $request): array
    {
        if (isset($this->user)) {
            $emailData = $this->contentEmailData($request);

            try {
                return $this->emailApi->sendEmail($emailData);
            } catch (ErrorMailSenderException $e) {
                throw new ErrorMailSenderException(self::ERROR_SENDING, self::HTTP_ERROR_500, $e);
            }
        }

        throw new ErrorAuthException(self::ERROR_AUTH, self::HTTP_ERROR_401);
    }

    /**
     * @return array<string, mixed>
     */
    public function contentEmailData(RequestEmailSender $request): array
    {
        return [
            'subject' => $request->mail->getSubject(),
            'sender' => $request->mail->getSenderData(),
            'to' => $request->mail->getRecipientData(),
            'htmlContent' => $request->mail->getHtmlContent(),
            'attachment' => $request->mail->getAttachment(),
        ];
    }
}