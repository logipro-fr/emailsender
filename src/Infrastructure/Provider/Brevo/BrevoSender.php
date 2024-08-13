<?php

namespace EmailSender\Infrastructure\Provider\Brevo;

use EmailSender\Application\Service\SendMail\EmailApiInterface;
use EmailSender\Application\Service\SendMail\SendMailRequest;
use EmailSender\Domain\Model\Mail\Mail;
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
        $data = json_encode([
            "sender" => $emailToSend->getSenderData(),
            "to" => [$emailToSend->getRecipientData(0)],
            "subject" => $emailToSend->getSubject(),
            "htmlContent" => $emailToSend->getHtmlContent()
        ]);
        $options = [
            'headers' => [
                'accept' => 'application/json',
                'api-key' => $_ENV['BREVO_API_KEY'],
                'content-type' => 'application/json',
            ],
            'body' => $data,
        ];
        $response = $this->client->request('POST', $url, $options);
        if ($response->getStatusCode() ===  200 || $response->getStatusCode() ===  201) {
            return true;
        } elseif ($response->getStatusCode() >= 400) {
            throw new BadRequestException();
        }
        return false;
    }
}
