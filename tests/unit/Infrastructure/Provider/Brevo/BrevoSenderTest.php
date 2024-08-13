<?php

namespace EmailSender\Tests\Infrastructure\Brevo;

use EmailSender\Application\Service\SendMail\MailFactory;
use EmailSender\Application\Service\SendMail\SendMailRequest;
use EmailSender\Domain\Model\Mail\Mail;
use PHPUnit\Framework\TestCase;
use EmailSender\Infrastructure\Provider\Brevo\BrevoSender;
use EmailSender\Infrastructure\Shared\CurrentWorkDirPath;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

use function Safe\file_get_contents;

class BrevoSenderTest extends TestCase
{
    private Mail $request;

    protected function setUp(): void
    {
        $this->request = (new MailFactory())->buildMailFromRequest(new SendMailRequest(
            "Pedro, pedro@gmail.com",
            ["Pedro, pedro@gmail.com", "Mathis, Mathis@gmail.com"],
            "Email test",
            "<html><body><h1>This is a test email</h1></body></html>",
            "Brevo"
        ));
    }

    public function testSendEmailSuccess(): void
    {
        $mockResponse = new MockResponse(
            file_get_contents(CurrentWorkDirPath::getPath() .
            '/tests/ressources/brevoResponse.json'),
            ['http_code' => 201],
        );

        $mockHttpClient = new MockHttpClient($mockResponse, "https://api.brevo.com/v3/smtp/email");
        $provider = new BrevoSender($mockHttpClient);
        $response = $provider->sendMail($this->request);
        $this->assertTrue($response);
    }

    public function testSendEmailFailure(): void
    {
        $this->expectException(BadRequestException::class);

        $mockResponse = new MockResponse(
            file_get_contents(CurrentWorkDirPath::getPath() .
            '/tests/ressources/brevoResponse.json'),
            ['http_code' => 400],
        );

        $mockHttpClient = new MockHttpClient($mockResponse, "https://api.brevo.com/v3/smtp/email");
        $provider = new BrevoSender($mockHttpClient);
        $provider->sendMail($this->request);
    }
}
