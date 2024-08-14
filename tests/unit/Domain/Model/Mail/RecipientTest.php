<?php

namespace EmailSender\Tests\Domain\Mail;

use EmailSender\Domain\Model\Mail\Contact;
use EmailSender\Domain\Model\Mail\Recipient;
use InvalidArgumentException;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class RecipientTest extends TestCase
{
    public function testGetRecipients(): void {
        $contacts = [
            new Contact('John Doe', 'john.doe@example.com'),
            new Contact('Jane Smith', 'jane.smith@example.com'),
        ];
        $recipient = new Recipient($contacts);
        $this->assertEquals('Jane Smith', $recipient->getRecipients()[1]->getName());
        $this->assertEquals('jane.smith@example.com', $recipient->getRecipients()[1]->getAddress());
        $this->assertEquals('John Doe', $recipient->getRecipients()[0]->getName());
        $this->assertEquals('john.doe@example.com', $recipient->getRecipients()[0]->getAddress());
    }
    
    public function testRecipientCannotBeEmpty(): void
    {
        $this->expectExceptionMessage("Recipients cannot be empty");
        $this->expectException(InvalidArgumentException::class);
        (new Recipient([]));
    }

    public function testToString(): void
    {

        $contacts = [
            new Contact('John Doe', 'john.doe@example.com'),
            new Contact('Jane Smith', 'jane.smith@example.com'),
        ];
        $recipient = new Recipient($contacts);
        $output = $recipient->__toString();
        $expectedOutput = ' 1. John Doe john.doe@example.com 2. Jane Smith jane.smith@example.com';
        $this->assertEquals($output, $expectedOutput);
    }


}
