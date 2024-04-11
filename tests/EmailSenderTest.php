<?php

namespace EmailSenderTest;

use PHPUnit\Framework\TestCase;
use EmailSender\EmailSender;

class EmailSenderTest extends TestCase
{
    public const HELLO_WORLD = "Hello World";

    public function testEmailSender(): void
    {
        $emailSender = (new EmailSender())->execute();
        $this->assertEquals(self::HELLO_WORLD, $emailSender);
    }
}
