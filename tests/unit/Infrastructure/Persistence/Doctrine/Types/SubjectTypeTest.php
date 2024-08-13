<?php

namespace EmailSender\Tests\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\SqlitePlatform;
use EmailSender\Domain\Model\Mail\Subject;
use EmailSender\Infrastructure\Persistence\Doctrine\Types\SubjectType;
use PHPUnit\Framework\TestCase;

class SubjectTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals("subject", (new SubjectType())->getName());
    }

    public function testConvertValue(): void
    {
        $type = new SubjectType();
        $dbValue = $type->convertToDatabaseValue(
            $content = new Subject("ceci est un content de test"),
            new SqlitePlatform()
        );

        $this->assertIsString($dbValue);
        $phpValue = $type->convertToPHPValue($dbValue, new SqlitePlatform());
        $this->assertEquals($content, $phpValue);
    }
    public function testSqlDeclaration(): void
    {
        $this->assertEquals('text', (new SubjectType())->getSQLDeclaration([], new SqlitePlatform()));
    }
}
