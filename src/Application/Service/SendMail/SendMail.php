<?php

namespace EmailSender\Application\Service\SendMail;

use EmailSender\Domain\EmailSenderRepositoryInterface;
use EmailSender\Domain\Model\Mail\MailId;

class SendMail
{
    private SendMailResponse $response;
    private const REQUEST_NULL_MESSAGE = "Request cannot be null";

    public function __construct(
        private EmailSenderRepositoryInterface $respository,
        private EmailApiInterface $emailApi,
        private string $mailId = ""
    ) {
    }

    public function execute(SendMailRequest $request): void
    {

        if ($request == null) {
            throw new \InvalidArgumentException(self::REQUEST_NULL_MESSAGE);
        }
        $mail = (new MailFactory())->buildMailFromRequest($request, new MailId($this->mailId));
        $this->emailApi->sendMail($request);

        $this->respository->add($mail);
        $this->response = new SendMailResponse($mail->getMailId());
    }

    public function getResponse(): SendMailResponse
    {
        return $this->response;
    }
}
