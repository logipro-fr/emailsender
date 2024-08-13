<?php

namespace EmailSender\Tests\Domain;

use EmailSender\Domain\Model\Mail\HtmlContent;
use PHPUnit\Framework\TestCase;

class HtmlContentTest extends TestCase
{
    public function testHtmlContent(): void
    {
        $this->assertEquals("", (new HtmlContent())->getHtmlContent());
    }
}
