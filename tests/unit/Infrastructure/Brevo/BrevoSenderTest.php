<?php

namespace EmailSender\Tests\Infrastructure\Brevo;

use EmailSender\Application\Service\SendMail\EmailSender;
use EmailSender\Application\Service\SendMail\EmailApiInterface;
use EmailSender\Application\Service\SendMail\RequestEmailSender;
use EmailSender\Domain\Attachment;
use EmailSender\Domain\Contact;
use EmailSender\Domain\HtmlContent;
use EmailSender\Domain\Mail;
use EmailSender\Domain\Recipient;
use EmailSender\Domain\Sender;
use EmailSender\Domain\Subject;
use PHPUnit\Framework\TestCase;
use EmailSender\Infrastructure\Brevo\BrevoSender;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class BrevoSenderTest extends TestCase
{
    private const API_KEY = 'api_key';

    public function testSendEmailSuccess(): void
    {

        $brevoApi = new BrevoSender(self::API_KEY, $this->createMockGuzzle(200));

        $requestData = new Mail(
            new Subject('Test Email Infra'),
            new Sender(new Contact("Sender Name", "sender@example.com")),
            new Recipient([new Contact("morgan chemarin", "morgan.chemarin@logipro.com")]),
            new HtmlContent('<html><body><h1>This is a test email</h1></body></html>'),
            new Attachment([])
        );



        $arrayData = (new EmailSender($brevoApi))->contentEmailData(new RequestEmailSender($requestData));
        $response = $brevoApi->sendEmail($arrayData);

        $this->assertEquals(['messageId' => '1234'], $response);
    }

    public function testSendEmailFailure(): void
    {

        $brevoApi = new BrevoSender(self::API_KEY, $this->createMockGuzzle(500));

        $this->expectException(\Exception::class);
        $brevoApi->sendEmail([]);
    }

    private function createMockGuzzle(int $statusCode): Client
    {
        $mock = new MockHandler([
            new Response($statusCode, [], strval(json_encode(['messageId' => '1234']))),
        ]);
        $handlerStack = HandlerStack::create($mock);
        return new Client(['handler' => $handlerStack]);
    }
}
