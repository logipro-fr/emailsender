<?php

namespace EmailSender\Tests\Domain\Mail;

use EmailSender\Domain\Model\Mail\Contact;
use EmailSender\Domain\Model\Mail\Exceptions\InvalidSenderArgumentsException;
use EmailSender\Domain\Model\Mail\Sender;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    public function testSender(): void
    {
        $contact = new Sender(new Contact("Morgan Chemarin", "morgan.chemarin@test.com"));
        $this->assertInstanceOf(Sender::class, $contact);
        $this->assertEquals("Morgan Chemarin", $contact->getSenderName());
        $this->assertEquals("morgan.chemarin@test.com", $contact->getSenderAddress());
    }
}
