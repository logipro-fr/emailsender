<?php

namespace EmailSender\Tests\Domain;

use ArgumentCountError;
use EmailSender\Application\Service\SendMail\Exceptions\MalformedAddressException;
use EmailSender\Domain\Contact;
use EmailSender\Domain\Sender;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    public function testSender(): void
    {
        $contact = new Sender(new Contact("Morgan Chemarin", "morgan.chemarin@logipro.com"));
        $this->assertInstanceOf(Sender::class, $contact);
        $this->assertEquals("Morgan Chemarin", $contact->getSenderName());
        $this->assertEquals("morgan.chemarin@logipro.com", $contact->getSenderAddress());
    }
}
