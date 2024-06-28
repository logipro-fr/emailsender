<?php

namespace EmailSender\Tests\Domain;

use EmailSender\Application\Service\SendMail\Exceptions\MalformedAddressException;
use EmailSender\Domain\Contact;
use InvalidArgumentException;
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

    public function testContactAddressCannotBeEmpty(): void
    {
        $this->expectExceptionMessage("Contact address cannot be empty");
        $this->expectException(InvalidArgumentException::class);
        (new Contact("Sender Name", ""))->getAddress();
    }

    public function testContactNameCannotBeEmpty(): void
    {
        $this->expectExceptionMessage("Contact name cannot be empty");
        $this->expectException(InvalidArgumentException::class);
        (new Contact("", "sender@name.com"))->getName();
    }

    public function testGetAddressThrowsExceptionForInvalidAddress(): void
    {
        $name = 'Jane Smith';
        $invalidAddress = 'invalid-email-address';
        $contact = new Contact($name, $invalidAddress);
        $this->expectException(MalformedAddressException::class);
        $contact->getAddress();
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
