<?php

namespace EmailSender\Application\Service\SendMail;

use EmailSender\Domain\Model\Mail\EmailSenderRepositoryInterface;
use EmailSender\Domain\Model\Mail\MailId;
use Infection\Configuration\Schema\InvalidFile;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class SendMail
{
    private SendMailResponse $response;
    private const REQUEST_NULL_MESSAGE = "Request cannot be null";

    public function __construct(
        private EmailSenderRepositoryInterface $respository,
        private AbstractFactoryEmailProvider $emailProviderFactory,
        private string $mailId = ""
    ) {
    }

    public function execute(SendMailRequest $request): void
    {

        if ($request == null) {
            throw new \InvalidArgumentException(self::REQUEST_NULL_MESSAGE);
        }

        $mail = (new MailFactory())->buildMailFromRequest($request, new MailId($this->mailId));


        $providerResponse = $this->emailProviderFactory
        ->buildProvider($request->provider)
        ->sendMail($mail);

        if ($providerResponse) {
            $this->respository->add($mail);
            $this->response = new SendMailResponse($mail->getMailId());
        } else {
            $this->response = new SendMailResponse("");
        }
    }

    public function getResponse(): SendMailResponse
    {
        return $this->response;
    }
}
