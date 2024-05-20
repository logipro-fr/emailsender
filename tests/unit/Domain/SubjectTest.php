<?php

namespace EmailSender\Tests\Domain;

use EmailSender\Domain\Subject;
use PHPUnit\Framework\TestCase;

class SubjectTest extends TestCase
{
    public function testSubject(): void
    {

        $this->assertEquals("", (new Subject())->getSubject());
    }
}
