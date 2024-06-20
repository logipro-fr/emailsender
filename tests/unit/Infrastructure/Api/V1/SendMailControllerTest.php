<?php

namespace EmailSender\Infrastructure\Api\V1;

use EmailSender\Application\Service\SendMail\EmailApiInterface;
use EmailSender\Application\Service\SendMail\EmailSender;
use EmailSender\Application\Service\SendMail\RequestEmailSender;
use EmailSender\Infrastructure\Persistance\EmailSenderRepositoryInMemory;
use EmailSender\Infrastructure\Provider\Brevo\BrevoSender;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SendMailControllerTest extends WebTestCase
{
    public function testSendMailSuccess(): void
    {
        $respository = new EmailSenderRepositoryInMemory();
        $apiMock = $this->createMock(EmailApiInterface::class);
        $apiMock->method('sendMail')->willReturn(true);
        $emailSender = new EmailSender($respository, $apiMock);


        $request = $this->buildRequest();
        $controller = new SendMailController($emailSender);
        $response = $controller->execute($request);
        $responseContent = $response->getContent();

        $decodedResponse = json_decode($responseContent, true);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($responseContent);
        $this->assertArrayHasKey('mailId', $decodedResponse);
        $this->assertStringStartsWith("mai_", $decodedResponse['mailId']);
    }

    private function buildRequest(): Request
    {
        return new Request([], [], [], [], [], [], json_encode([
            'subject' => 'Test Subject',
            'sender' => ['email' => 'sender@example.com', 'name' => 'Sender Name'],
            'to' => [['email' => 'recipient@example.com', 'name' => 'Recipient Name']],
            'htmlContent' => '<p>Test Content</p>',
            'attachment' => []
        ]));
    }
}
