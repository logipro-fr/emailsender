<?php

namespace EmailSender\Tests\Domain\Mail;

use EmailSender\Domain\Model\Mail\Subject;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SubjectTest extends TestCase
{
    public function testSubject(): void
    {
        $this->assertEquals("Objet d'un mail", (new Subject("Objet d'un mail"))->getSubject());
    }

    public function testSubjectIsEmpty(): void
    {
        $this->expectExceptionMessage("Subject cannot be empty");
        $this->expectException(InvalidArgumentException::class);
        new Subject("");
    }
}
