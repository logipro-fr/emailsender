<?php

namespace EmailSender\Tests\Integration\Infrastructure\Api;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use EmailSender\Domain\Model\Mail\EmailSenderRepositoryInterface;
use EmailSender\Domain\Model\Mail\MailId;
use EmailSender\Infrastructure\Persistence\Mail\EmailSenderRepositoryDoctrine;
use EmailSender\Infrastructure\Shared\Symfony\Kernel;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

use function Safe\json_encode;

class SendMailControllerTest extends WebTestCase {

    public const TEST_SENDER_VALUE = "Pedro, pedrobesseofi@gmail.com";
    public const TEST_ONE_RECIPIENT_VALUE = "Pedro logipro, pedro.besse@logipro.com";
    public const TEST_SUBJECT = "Email Sender integration test";
    public const TEST_HTML_CONTENT = "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <div style='text-align: center;'>
        <h1 style='color: #4CAF50;'>Integration Test Email from Email Sender</h1>
        <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
        <p style='font-size: 16px;'>
            This is an integration test email. You can safely ignore this message.
        </p>
        <p style='font-size: 14px; color: #888;'>
            This message was generated automatically as part of our testing process. No action is required on your part.
        </p>
        </div>
        <footer style='text-align: center; font-size: 12px; color: #aaa; margin-top: 20px;'>
        &copy; 2024 Email Sender, Inc. - All rights reserved.
        </footer>
    </body>
    </html>";
    public const TEST_PROVIDER = "Brevo";

    use DoctrineRepositoryTesterTrait;

    private KernelBrowser $client;
    private EmailSenderRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["mails"]);

        $this->client = static::createClient(["debug" => false]);

        /** @var EmailSenderRepositoryDoctrine $autoInjectedRepo */
        $autoInjectedRepo = $this->client->getContainer()->get("email.repository");
        $this->repository = $autoInjectedRepo;
    }

    public function testControllerErrorResponse(): void {
        $this->client->request(
            "POST",
            "/api/v1/email/send",
            [],
            [],
            ["CONTENT_TYPE" => "application/json"],
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

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $responseCode);
        $this->assertStringContainsString('"success":false', $responseContent);
    }

    public function testSendMailToOnePerson(): void {
        $this->client->request(
            "POST",
            "api/v1/email/send",
            [],
            [],
            ["CONTENT_TYPE" => "application/json"],
            json_encode([
                "sender" => self::TEST_SENDER_VALUE,
                "recipient" => [self::TEST_ONE_RECIPIENT_VALUE],
                "subject" => self::TEST_SUBJECT,
                "content" => self::TEST_HTML_CONTENT,
                "provider" => self::TEST_PROVIDER
            ])
        );

        /** @var string */
        $responseContent = $this->client->getResponse()->getContent();
        $responseCode = $this->client->getResponse()->getStatusCode();
        /** @var array<mixed,array<mixed>> */
        $array = json_decode($responseContent, true);
        /** @var string */
        $mailId = $array['data']['mailId'];

        $repo = new EmailSenderRepositoryDoctrine($this->getEntityManager());
        $mail = $repo ->findById(new MailId($mailId));

        $this->assertEquals(Response::HTTP_CREATED, $responseCode);
        $this->assertStringContainsString('"success":true', $responseContent);
        $this->assertStringContainsString('"ErrorCode":', $responseContent);
        $this->assertEquals($mail->getHtmlContent()->getHtmlContent(), self::TEST_HTML_CONTENT);

    }
}