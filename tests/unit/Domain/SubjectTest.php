<?php

namespace EmailSender\Tests\Domain;

use EmailSender\Domain\Subject;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SubjectTest extends TestCase
{
    public function testSubject(): void
    {
        $this->assertEquals("Objet d'un mail", (new Subject("Objet d'un mail"))->getSubject());
    }

    public function testSubjectIsNotEmpty(): void
    {
        $this->expectExceptionMessage("Subject cannot be empty");
        $this->expectException(InvalidArgumentException::class);
        (new Subject(""))->getSubject();
    }
}
