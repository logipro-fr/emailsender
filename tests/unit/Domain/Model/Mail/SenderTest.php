<?php

namespace EmailSender\Tests\Domain\Mail;

use EmailSender\Domain\Model\Mail\Contact;
use EmailSender\Domain\Model\Mail\Sender;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    public function testSender(): void
    {
        $sender = new Sender(new Contact("Morgan Chemarin", "morgan.chemarin@test.com"));
        $this->assertInstanceOf(Sender::class, $sender);
        $this->assertEquals("Morgan Chemarin", $sender->getSenderName());
        $this->assertEquals("morgan.chemarin@test.com", $sender->getSenderAddress());
    }

    public function testGetSenderData(): void {

        $sender = new Sender(new Contact("Morgan Chemarin", "morgan.chemarin@test.com"));
        $expectedOutput = ['name' => "Morgan Chemarin", "email" => "morgan.chemarin@test.com"];
        $this->assertEquals($sender->getSenderData(), $expectedOutput);
        $this->assertEquals("morgan.chemarin@test.com", $sender->getSenderAddress());
    }
}
