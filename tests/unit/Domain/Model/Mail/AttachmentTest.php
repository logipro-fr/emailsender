<?php

namespace EmailSender\Tests\Domain\Mail;

use EmailSender\Domain\Model\Mail\Attachment;
use PHPUnit\Framework\TestCase;

class AttachmentTest extends TestCase
{
    public function testAttachment(): void
    {
        $this->assertEquals([], (new Attachment())->getAttachment());
    }
}
