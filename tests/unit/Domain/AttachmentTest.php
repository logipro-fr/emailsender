<?php

namespace EmailSender\Tests\Domain;

use EmailSender\Domain\Attachment;
use PHPUnit\Framework\TestCase;

class AttachmentTest extends TestCase
{
    public function testAttachment(): void
    {
        $this->assertEquals([], (new Attachment())->getAttachment());
    }
}
