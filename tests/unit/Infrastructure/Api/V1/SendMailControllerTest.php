<?php

namespace EmailSender\Tests\Infrastructure\Api\V1;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use EmailSender\Domain\Model\Mail\EmailSenderRepositoryInterface;
use EmailSender\Domain\Model\Mail\MailId;
use EmailSender\Infrastructure\Api\V1\SendMailController;
use EmailSender\Infrastructure\Persistence\Mail\EmailSenderRepositoryDoctrine;
use EmailSender\Infrastructure\Persistence\Mail\EmailSenderRepositoryInMemory;
use EmailSender\Infrastructure\Provider\FactoryEmailProvider;
use EmailSender\Infrastructure\Shared\CurrentWorkDirPath;
use stdClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Request;

use function Safe\file_get_contents;
use function Safe\json_decode;
use function Safe\json_encode;

class SendMailControllerTest extends WebTestCase
{
    use DoctrineRepositoryTesterTrait;

    private KernelBrowser $client;
    private EmailSenderRepositoryInterface $repository;


    public function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["mails"]);

        $this->client = static::createClient(["debug" => false]);

        /** @var EmailSenderRepositoryDoctrine */
        $autoInjectedRepo = $this->client->getContainer()->get("email.repository");
        $this->repository = $autoInjectedRepo;
    }

    public function testControllerRouting(): void
    {

        $this->client->request(
            "POST",
            "/api/v1/email/send",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "sender" => "Pedro, pedro@gmail.com",
                "recipient" => ["Pedro, pedro@gmail.com", "Mathis, Mathis@gmail.com"],
                "subject" => "Email test",
                "content" => "<html><body><h1>This is a test email</h1></body></html>",
                "provider" => 'testProvider'
            ])
        );
        /** @var string */
        $responseContent = $this->client->getResponse()->getContent();
        $responseCode = $this->client->getResponse()->getStatusCode();


        /** @var array<mixed,array<mixed>> */
        $array = json_decode($responseContent, true);
        /** @var string */
        $mailId = $array['data']['mailId'];
        $mail = $this->repository->findById(new MailId($mailId));

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('"success":true', $responseContent);
        $this->assertEquals(201, $responseCode);
        $this->assertStringContainsString('"ErrorCode":', $responseContent);
        $this->assertStringContainsString('"mailId":"mai_', $responseContent);
        $this->assertStringContainsString('"message":"', $responseContent);
        $this->assertStringStartsWith("mai_", $mail->getMailId());
        $this->assertEquals($mailId, $mail->getMailId());
    }

    public function testControllerException(): void
    {
        $this->client->request(
            "POST",
            "/api/v1/email/send",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "sender" => "",
                "recipient" => [],
                "subject" => "",
                "content" => "",
                "provider" => 'testProvider'
            ])
        );
        /** @var string */
        $responseContent = $this->client->getResponse()->getContent();
        $responseCode = $this->client->getResponse()->getStatusCode();

        $this->assertStringContainsString('"success":false', $responseContent);
        $this->assertEquals(400, $responseCode);
        $this->assertStringContainsString('"ErrorCode":"InvalidArgumentException"', $responseContent);
        $this->assertStringContainsString('"data":""', $responseContent);
        $this->assertStringContainsString('"message":"Subject cannot be empty"', $responseContent);
    }

    public function testSendMailControllerExecute(): void
    {

        $mockResponse = [
            new MockResponse(
                file_get_contents(
                    CurrentWorkDirPath::getPath() .
                    '/tests/ressources/brevoResponse.json'
                ),
                ['http_code' => 201]
            ),
        ];
        $mockClient = new MockHttpClient($mockResponse);
        $providerFactory = new FactoryEmailProvider($mockClient);
        $repository = new EmailSenderRepositoryInMemory();
        $controller = new SendMailController($repository, $providerFactory, $this->getEntityManager());
        $request = Request::create(
            "/api/v1/email/send",
            "POST",
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "sender" => "Pedro, pedro@gmail.com",
                "recipient" => ["Pedro, pedro@gmail.com", "Mathis, Mathis@gmail.com"],
                "subject" => "Email test",
                "content" => "<html><body><h1>This is a test email</h1></body></html>",
                "provider" => "Brevo",
            ])
        );
        $response = $controller->execute($request);
        /** @var string */
        $responseContent = $response->getContent();
        /** @var array<string, bool> */
        $responseContentJson = json_decode($responseContent, true);
        /** @var bool $expectedBool */
        $expectedBool = $responseContentJson['success'];
        $responseStatus = $response->getStatusCode();
        $this->assertJson($responseContent);
        $this->assertEquals(true, $expectedBool);
        $this->assertEquals(201, $responseStatus);
    }
}
