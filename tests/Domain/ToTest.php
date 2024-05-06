<?php

namespace EmailSender\Tests\Domain;

use EmailSender\Domain\To;
use PHPUnit\Framework\TestCase;

class ToTest extends TestCase
{
    public function testTo(): void
    {
        $this->assertEquals([], (new To())->getTo());
    }
}
