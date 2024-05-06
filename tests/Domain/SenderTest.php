<?php

namespace EmailSender\Tests\Domain;

use EmailSender\Domain\Sender;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    public function testSender(): void
    {
        $this->assertEquals([], (new Sender([]))->getSender());
    }
}
