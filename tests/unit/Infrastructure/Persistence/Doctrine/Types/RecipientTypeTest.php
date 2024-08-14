<?php
namespace EmailSender\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use EmailSender\Domain\Model\Mail\Contact;
use EmailSender\Domain\Model\Mail\Recipient;
use EmailSender\Infrastructure\Persistence\Doctrine\Types\RecipientType;
use PHPUnit\Framework\TestCase;

class RecipientTypeTest extends TestCase {


    public function testGetName(): void {
        $this->assertEquals('recipient' , (new RecipientType())->getName());
    }
    
    public function testConvertToPhpValue(): void {
        $type = new RecipientType();

        $jsonData = '[{"email":"test@gmail.com","name":"test"}]';
        
        // Convertir depuis la base de données vers un objet PHP
        $recipient = $type->convertToPHPValue($jsonData, new SqlitePlatform());

        $this->assertInstanceOf(Recipient::class, $recipient);

        $contacts = $recipient->getRecipients();
        
        $this->assertCount(1, $contacts);
        $this->assertEquals('test@gmail.com', $contacts[0]->getAddress());
        $this->assertEquals('test', $contacts[0]->getName());
    }

    public function testConvertToDatabaseValue(): void {
        $type = new RecipientType();

        $recipient = new Recipient([new Contact("test", "test@gmail.com")]);

        // Convertir depuis un objet PHP vers une chaîne pour la base de données
        $databaseValue = $type->convertToDatabaseValue($recipient, new SqlitePlatform());

        $expectedJson = '[{"email":"test@gmail.com","name":"test"}]';
        $this->assertJsonStringEqualsJsonString($expectedJson, $databaseValue);
    }
}

