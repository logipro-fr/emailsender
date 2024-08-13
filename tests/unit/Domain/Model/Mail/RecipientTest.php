<?php

namespace EmailSender\Tests\Domain\Mail;

use EmailSender\Domain\Model\Mail\Contact;
use EmailSender\Domain\Model\Mail\Recipient;
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
        (new Recipient([]));
    }


    public function testGetRecipientAddressException(): void
    {

        $contacts = [
            new Contact('John Doe', 'john.doe@example.com'),
            new Contact('Jane Smith', 'jane.smith@example.com'),
        ];
        $recipient = new Recipient($contacts);

        $this->expectException(OutOfBoundsException::class);
        $recipient->getRecipientAddress(2);
    }

    public function testGetRecipientNameException(): void
    {

        $contacts = [
            new Contact('John Doe', 'john.doe@example.com'),
            new Contact('Jane Smith', 'jane.smith@example.com'),
        ];
        $recipient = new Recipient($contacts);

        $this->expectException(OutOfBoundsException::class);
        $recipient->getRecipientName(2);
    }
}
