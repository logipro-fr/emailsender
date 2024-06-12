<?php

namespace EmailSender\Tests\Infrastructure\Brevo;

use EmailSender\Application\Service\SendMail\Exceptions\ErrorMailSenderException;
use EmailSender\Application\Service\SendMail\RequestEmailSender;
use EmailSender\Domain\Mail;
use EmailSender\Domain\Model\Mail\MailId;
use PHPUnit\Framework\TestCase;
use EmailSender\Infrastructure\Provider\Brevo\BrevoSender;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class BrevoSenderTest extends TestCase
{
    private const API_KEY = 'api_key';

    private RequestEmailSender $request;

    public function setUp(): void
    {
        $this->request = new RequestEmailSender(
            "Pedro, pedro@gmail.com",
            ["Pedro, pedro@gmail.com", "Mathis, Mathis@gmail.com"],
            "Email test",
            "<html><body><h1>This is a test email</h1></body></html>",
        );
    }

    public function testSendEmailSuccess(): void
    {
        $brevoApi = new BrevoSender(self::API_KEY, $this->createMockGuzzle(200));
        $response = $brevoApi->sendMail($this->request);
        $this->assertTrue($response);
    }

    public function testSendEmailFailure(): void
    {
        $this->expectException(ErrorMailSenderException::class);
        $this->expectExceptionMessage("Erreur lors de l'envoi du mail");
        (new BrevoSender(self::API_KEY, $this->createMockGuzzle(500)));
    }



    private function createMockGuzzle(int $statusCode): Client
    {
        if ($statusCode == 500) {
            throw new ErrorMailSenderException("Erreur lors de l'envoi du mail");
        }

        $mock = new MockHandler([
            new Response($statusCode, [], strval(json_encode(['messageId' => '1234']))),
        ]);
        $handlerStack = HandlerStack::create($mock);
        return new Client(['handler' => $handlerStack]);
    }
}
