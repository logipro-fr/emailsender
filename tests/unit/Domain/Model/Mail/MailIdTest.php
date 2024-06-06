<?php

namespace EmailSender\Tests\Domain\Model\Mail;

use EmailSender\Domain\Model\Mail\MailId;
use PHPUnit\Framework\TestCase;

class MailIdTest extends TestCase
{
    public function testIdentify(): void
    {
        $id1 = new MailId();
        $id2 = new MailId();
        $this->assertStringStartsWith("mai_", $id1);
        $this->assertFalse($id1->equals($id2));
    }

    public function testCustomId(): void
    {
        $id1 = new MailId("test");
        $this->assertEquals("test", $id1);
    }
}
