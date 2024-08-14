<?php

namespace EmailSender\Infrastructure\Provider\Brevo;

use EmailSender\Application\Service\SendMail\EmailApiInterface;
use EmailSender\Domain\Model\Mail\Mail;
use EmailSender\Domain\Model\Mail\Recipient;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Safe\json_encode;

class BrevoSender implements EmailApiInterface
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    public function sendMail(Mail $emailToSend): bool
    {
        $url = 'https://api.brevo.com/v3/smtp/email';

        $response = $this->client->request(
            'POST',
            $url,
            $this->buildSendMailRequest($emailToSend)
        );
        if ($response->getStatusCode() ===  200 || $response->getStatusCode() ===  201) {
            return true;
        } elseif ($response->getStatusCode() >= 400) {
            throw new BadRequestException();
        }
        return false;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSendMailRequest(Mail $emailToSend): array
    {
        $data = json_encode([
            "sender" => $emailToSend->getSender()->getSenderData(),
            "to" => $this->buildRecipientData($emailToSend->getRecipient()),
            "subject" => $emailToSend->getSubject()->getSubject(),
            "htmlContent" => $emailToSend->getHtmlContent()->getHtmlContent(),
        ]);
        $options = [
            'headers' => [
                'accept' => 'application/json',
                'api-key' => $_ENV['BREVO_API_KEY'],
                'content-type' => 'application/json',
            ],
            'body' => $data,
        ];
        return $options;
    }

    /**
     * @param Recipient $recipient
     * @return array<int, array<string, string>>
     */
    private function buildRecipientData(Recipient $recipient): array
    {
        $result = [];
        foreach ($recipient->getRecipients() as $recipient) {
            $result[] = $recipient->getContactData();
        }
        return $result;
    }
}
