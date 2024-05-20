<?php

namespace EmailSender\Tests\Domain;

use EmailSender\Application\Service\SendMail\Exceptions\MalformedAddressException;
use EmailSender\Domain\Contact;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    public function testCreateAUsualEmailAdress(): void
    {
        $sut = new Contact("Morgan Chemarin", "morgan.chemarin@logipro.com");
        $this->assertEquals("Morgan Chemarin", $sut->getName());
        $this->assertEquals("morgan.chemarin@logipro.com", $sut->getAddress());
    }

    public function testMalformedAdressException(): void
    {

        foreach ($this->badAdresses() as $bad) {
            try {
                $sut = new Contact("Bernard Hinault", $bad);
                $this->assertInstanceOf(Contact::class, $sut);
            } catch (MalformedAddressException $e) {
                $this->assertInstanceOf(MalformedAddressException::class, $e);
            }
        }
    }

    /**
     * @return array<string>
     */
    public function badAdresses(): array
    {
        return [
            // without @
            'invalidemail.com',
            // with a special character (like °)
            'user°@example.com',
            // with a space
            'user name@example.com',
            // with invalid domain name
            'user@example_com',
            // without a local part (nothing before the @)
            '@example.com',
            // with unauthorized Unicode character (like ❌)
            'user❌@example.com',
            // with excessive length
            'user1234567890123456789012345678901234567890123456789012345678901234567890@example.com',
            // with excessive domain length :
            'user@example1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890.com'
        ];
    }
}
