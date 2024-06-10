<?php

namespace EmailSender\Application\Service\SendMail;

use EmailSender\Application\Service\SendMail\Exceptions\ErrorAuthException;
use EmailSender\Application\Service\SendMail\Exceptions\ErrorMailSenderException;
use EmailSender\Domain\EmailSenderRepositoryInterface;
use EmailSender\Domain\Model\Mail\MailId;

class EmailSender
{

    private ResponseSendMail $response;

    public function __construct(private EmailSenderRepositoryInterface $respository, private EmailApiInterface $emailApi, private string $mailId = "")
    {
    }

    public function execute(RequestEmailSender $request): void
    {

        if ($request == null) {
            throw new \InvalidArgumentException("Request cannot be null");
        }
        $mail = (new MailFactory())->buildMailFromRequest($request, new MailId($this->mailId));
        $this->emailApi->sendEmail($mail);

        $this->respository->add($mail);

        $this->response = new ResponseSendMail($mail->getMailId());
    }

    public function getResponse(): ResponseSendMail
    {
        return $this->response;
    }







































    // /**
    //  * @return array<string, mixed>
    //  */
    // public function contentEmailData(RequestEmailSender $request): array
    // {
    //     return [
    //         'subject' => $request->mail->getSubject(),
    //         'sender' => $request->mail->getSenderData(),
    //         'to' => $request->mail->getRecipientData(),
    //         'htmlContent' => $request->mail->getHtmlContent(),
    //         'attachment' => $request->mail->getAttachment(),
    //     ];
    // }
    // /**
    //  * @return array<string, mixed>
    //  */
    // /*
    // public function sendMail(RequestEmailSender $request): array
    // {
    //     if (isset($this->user)) {
    //         $emailData = $this->contentEmailData($request);

    //         try {
    //             return $this->emailApi->sendEmail($emailData);
    //         } catch (ErrorMailSenderException $e) {
    //             throw new ErrorMailSenderException(self::ERROR_SENDING, self::HTTP_ERROR_500, $e);
    //         }
    //     }

    //     throw new ErrorAuthException(self::ERROR_AUTH, self::HTTP_ERROR_401);
    // }

    // $emailData = [
    //         'subject' => $request->mail->getSubject(),
    //         'sender' => $request->mail->getSenderData(),
    //         'to' => $request->mail->getRecipientData(),
    //         'htmlContent' => $request->mail->getHtmlContent(),
    //         'attachment' => $request->mail->getAttachment(),
    //     ];







    // public function __construct(string $apiKey, ClientInterface $client = new Client())
    // {
    //     $config = Configuration::getDefaultConfiguration()
    //         ->setApiKey(self::API_KEY, $apiKey);

    //     $this->apiInstance = new TransactionalEmailsApi($client, $config);
    // }


    // public function sendMail(RequestEmailSender $request): CreateSmtpEmail
    // {
    //     if (isset($this->user)) {
    //         $sendSmtpEmail = $this->contentSmtpEmail($request);

    //         try {
    //             return $this->apiInstance->sendTransacEmail($sendSmtpEmail);
    //         } catch (Exception $e) {
    //             throw new ErrorMailSenderException(self::ERROR_SENDING, self::HTTP_ERROR_500, $e);
    //         }
    //     }

    //     throw new ErrorAuthException(self::ERROR_AUTH, self::HTTP_ERROR_401);
    // }

    // public function contentSmtpEmail(RequestEmailSender $request): SendSmtpEmail
    // {
    //     return new SendSmtpEmail([
    //         'subject' => $request->mail->getSubject(),
    //         'sender' => $request->mail->getSenderData(),
    //         'to' => $request->mail->getRecipientData(),
    //         'htmlContent' => $request->mail->getHtmlContent(),
    //         'attachment' => $request->mail->getAttachment(),
    //     ]);
    // }*/
}















/*
    public function __construct(EmailApiInterface $emailApi)
    {
        $this->emailApi = $emailApi;
    }

    public function sendMail(RequestEmailSender $request): bool
    {
        if (isset($this->user)) {
            $mailData = $this->contentMailData($request);

            try {
                return $this->emailApi->sendMail($mailData);
            } catch (Exception $e) {
                throw new ErrorMailSenderException(self::ERROR_SENDING, self::HTTP_ERROR_500, $e);
            }
        }

        throw new ErrorAuthException(self::ERROR_AUTH, self::HTTP_ERROR_401);
    }

    public function contentMailData(RequestEmailSender $request): array
    {
        return [
            'subject' => $request->mail->getSubject(),
            'sender' => $request->mail->getSenderData(),
            'to' => $request->mail->getRecipientData(),
            'htmlContent' => $request->mail->getHtmlContent(),
            'attachment' => $request->mail->getAttachment(),
        ];
    }
*/
