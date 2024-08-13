<?php

namespace EmailSender\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use EmailSender\Domain\Model\Mail\MailId;
use EmailSender\Infrastructure\Persistence\Doctrine\Types\MailIdType;
use PHPUnit\Framework\TestCase;

class MailIdTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('mail_id', (new MailIdType())->getName());
    }

    public function testConvertToPHPValue(): void
    {
        $type = new MailIdType();
        $id = $type->convertToPHPValue("mai_", new SqlitePlatform());
        $this->assertEquals(true, $id instanceof MailId);
    }

    public function testConvertToDatabaseValue(): void
    {
        $type = new MailIdType();
        $dbValue = $type->convertToDatabaseValue($id = new MailId(), new SqlitePlatform());
        $this->assertEquals($id->__toString(), $dbValue);
    }
}
