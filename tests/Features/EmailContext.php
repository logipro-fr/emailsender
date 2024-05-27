<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use EmailSender\Application\Service\SendMail\EmailSender;
use EmailSender\Application\Service\SendMail\RequestEmailSender;
use EmailSender\Domain\Attachment;
use EmailSender\Domain\Contact;
use EmailSender\Domain\HtmlContent;
use EmailSender\Domain\Mail;
use EmailSender\Domain\Recipient;
use EmailSender\Domain\Sender;
use EmailSender\Domain\Subject;
use EmailSender\Infrastructure\Brevo\BrevoSender;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * Defines application features from the specific context.
 */
class EmailContext implements Context
{
    private const API_KEY = 'api-key';
    private const CURRENT_USER = "Mathis";
    private EmailSender $emailSender;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $brevoApi = new BrevoSender(self::API_KEY, $this->createMockGuzzle(200));
        $this->emailSender = new EmailSender($brevoApi);
    }

    /**
     * @Given I am an authenticated user
     */
    public function authenticatedUser(): void
    {
        $this->emailSender->isAuthenticated(self::CURRENT_USER);
    }

    /**
     * @Given I have access to the email sending API
     */
    public function accessEmailApi(): void
    {
    }

    /**
     * @When I send an email with the following details:
     */
    public function sendEmailWithDetails(TableNode $table): void
    {

        $detailsContext = $table->getHash()[0];

        $mail = new Mail(
            new Subject($detailsContext['subject']),
            new Sender(new Contact("Sender Name", "sender@example.com")),
            new Recipient([new Contact("Recipient Name", $detailsContext['recipient'])]),
            new HtmlContent($detailsContext['body']),
            new Attachment([])
        );

        $requestEmailSender = new RequestEmailSender($mail);

        $this->emailSender->sendMail($requestEmailSender);
    }


    /**
     * @Then the email is successfully sent
     */
    public function emailSuccessfullySent(): void
    {
    }

    /**
     * @Then I receive a confirmation of the sending
     */
    public function confirmationSending(): void
    {
    }


    protected function createMockGuzzle(int $statusCode): Client
    {
        $mock = new MockHandler([
            new Response($statusCode, [], strval(json_encode(['messageId' => '1234']))),
        ]);
        $handlerStack = HandlerStack::create($mock);
        return new Client(['handler' => $handlerStack]);
    }
}
