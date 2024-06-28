<?php

namespace EmailSender\Tests\Infrastructure\Api\V1;

use EmailSender\Infrastructure\Api\V1\SendMailController;
use EmailSender\Infrastructure\Persistance\EmailSenderRepositoryInMemory;
use EmailSender\Tests\WebBaseTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

use function Safe\json_encode;

class SendMailControllerTest extends WebTestCase
{
    private KernelBrowser $client;


    public function setUp(): void
    {
        $this->client = static::createClient(["debug" => false]);
    }

    public function testControllerRouting(): void
    {

        $this->client->request(
            "POST",
            "/api/v1/sendmail",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "sender" => "Pedro, pedro@gmail.com",
                "recipient" => ["Pedro, pedro@gmail.com", "Mathis, Mathis@gmail.com"],
                "subject" => "Email test",
                "content" => "<html><body><h1>This is a test email</h1></body></html>"
            ])
        );

        $responseContent = $this->client->getResponse()->getContent();
        $responseCode = $this->client->getResponse()->getStatusCode();

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"success":true', $responseContent);
        $this->assertEquals(200, $responseCode);
    }



    public function testSendMailControllerExecute(): void
    {
        $mock = new MockHandler([
            new Response(201, [], strval(json_encode(['messageId' => '1234']))),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $repository = new EmailSenderRepositoryInMemory();
        $controller = new SendMailController($repository, $client);
        $request = Request::create(
            "/api/v1/sendmail",
            "POST",
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "sender" => "Pedro, pedro@gmail.com",
                "recipient" => ["Pedro, pedro@gmail.com", "Mathis, Mathis@gmail.com"],
                "subject" => "Email test",
                "content" => "<html><body><h1>This is a test email</h1></body></html>"
            ])
        );


        $response = $controller->execute($request);
        /** @var string */
        $responseContent = $response->getContent();
        $responseStatus = $response->getStatusCode();

        $this->assertStringContainsString('"success":true', $responseContent);
        $this->assertEquals(201, $responseStatus);
        $this->assertStringContainsString('"MailId":"mai_', $responseContent);
    }
}
