<?php

namespace EmailSender\Tests\Domain;

use EmailSender\Application\Service\SendMail\Exceptions\MalformedAddressException;
use EmailSender\Domain\Contact;
use EmailSender\Domain\Recipient;
use PHPUnit\Framework\TestCase;

class RecipientTest extends TestCase
{
    public function testRecipient(): void
    {
        $contact = new Recipient([new Contact("Morgan Chemarin", "morgan.chemarin@logipro.fr")]);
        $this->assertEquals("Morgan Chemarin", $contact->getRecipientName(0));
        $this->assertEquals("morgan.chemarin@logipro.fr", $contact->getRecipientAddress(0));
    }
}
