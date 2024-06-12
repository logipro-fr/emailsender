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
     * @Given I want send a mail via an email provider
     */
    public function iWantSendAMailViaAnEmailProvider(): void
    {
        throw new PendingException();
    }

    /**
     * @When I send an email with the following details:
     */
    public function iSendAnEmailWithTheFollowingDetails(TableNode $table): void
    {
        throw new PendingException();
    }

    /**
     * @Then the email is successfully sent
     */
    public function theEmailIsSuccessfullySent(): void
    {
        throw new PendingException();
    }

    /**
     * @Then the email is marked as sent with its sent date
     */
    public function theEmailIsMarkedAsSentWithItsSentDate(): void
    {
        throw new PendingException();
    }
}
