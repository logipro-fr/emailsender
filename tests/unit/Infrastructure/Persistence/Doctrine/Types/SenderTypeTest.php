<?php
namespace EmailSender\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use EmailSender\Domain\Model\Mail\HtmlContent;
use EmailSender\Domain\Model\Mail\Sender;
use EmailSender\Infrastructure\Persistence\Doctrine\Types\SenderType;
use PHPUnit\Framework\TestCase;

class SenderTypeTest extends TestCase {
    public const TEST_SENDER_VALUE = "test test@gmail.com";


    public function testGetName(): void {
        $this->assertEquals('sender' , (new SenderType())->getName());
    }

    public function testConvertToPhpValue(): void {
        $type = new SenderType();
        $sender = $type->convertToPHPValue(self::TEST_SENDER_VALUE, new SqlitePlatform());
        $this->assertTrue($sender instanceof Sender);
        $this->assertEquals(self::TEST_SENDER_VALUE, $sender->__toString());
        $this->assertEquals("test", $sender->getSenderName());
    }

    public function testConvertToDatabaseValue(): void {
        $type = new SenderType();
        $databaseValue = $type->convertToDatabaseValue(
            $sender =  new HtmlContent(self::TEST_SENDER_VALUE), 
            new SqlitePlatform
        );

        $this->assertEquals($sender->__toString(), $databaseValue);
    }
}