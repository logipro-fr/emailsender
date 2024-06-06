<?php

namespace EmailSender\Tests\Domain;

use EmailSender\Domain\Contact;
use EmailSender\Domain\Recipient;
use InvalidArgumentException;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class RecipientTest extends TestCase
{
    public function testRecipient(): void
    {
        $contact = new Recipient([new Contact("Morgan Chemarin", "morgan.chemarin@logipro.fr")]);
        $this->assertEquals("Morgan Chemarin", $contact->getRecipientName(0));
        $this->assertEquals("morgan.chemarin@logipro.fr", $contact->getRecipientAddress(0));
    }

    public function testRecipientCannotBeEmpty(): void
    {
        $this->expectExceptionMessage("Recipients cannot be empty");
        $this->expectException(InvalidArgumentException::class);
        (new Recipient([]))->getRecipientName(0);
        (new Recipient([]))->getRecipientAddress(0);
    }

    public function testRecipientWithAnUnexistingRank(): void
    {
        $this->expectExceptionMessage("This recipient rank doesn't exist");
        $this->expectException(OutOfBoundsException::class);
        (new Recipient([new Contact("Morgan Chemarin", "morgan.chemarin@logipro.fr")]))->getRecipientName(-5);
        (new Recipient([new Contact("Morgan Chemarin", "morgan.chemarin@logipro.fr")]))->getRecipientAddress(-6);
    }
}
