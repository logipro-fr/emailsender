<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class EmailContext implements Context
{
    private string $authenticatedUser;
    private string $sendingApi;
    private string $mailWithDetails;
    private bool $mailSent;
    private string $mailConfirmation;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given I am an authenticated user
     */
    public function iAmAnAuthenticatedUser(): void
    {
        $this->authenticatedUser = 'Mathis';
    }

    /**
     * @Given I have access to the email sending API
     */
    public function iHaveAccessToTheEmailSendingApi(): void
    {
        $this->sendingApi = 'AccidentPrediction';
    }

    /**
     * @When I send an email with the following details:
     */
    public function iSendAnEmailWithTheFollowingDetails(TableNode $table)
    {
        $this->mailWithDetails = '| example@domain.com | Send Test | This is a send test |';
    }

    /**
     * @Then the email is successfully sent
     */
    public function theEmailIsSuccessfullySent()
    {
        $this->mailSent = true;
    }

    /**
     * @Then I receive a confirmation of the sending
     */
    public function iReceiveAConfirmationOfTheSending()
    {
        $this->mailConfirmation = 'Votre mail a été envoyé avec succès';
    }
}
