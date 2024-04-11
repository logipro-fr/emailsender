<?php

namespace EmailSender\Tests;

use PHPUnit\Framework\TestCase;
use EmailSender\EmailSender;

class EmailSenderTest extends TestCase
{
    public const HELLO_WORLD = "Hello World";

    public const USER_SENDER = "Mathis";
    public const RECEIVER_MAIL = "mathis.tallaron@logipro.com";
    public const SUBJECT_MAIL = "Test envoi";
    public const CONTENT_MAIL = "Ceci est le corps pour un test envoi";
    public const CONFIRMATION = "Le mail a été envoyé avec succès";

    public function testEmailSenderIsAuthenticated(): void
    {
        $emailSenderIsAuthenticated = (new EmailSender())->isAuthenticated(self::USER_SENDER);
        $this->assertEquals(self::USER_SENDER, $emailSenderIsAuthenticated);
    }


    public function testEmailSenderSendMail(): void
    {
        $emailSenderSendMail = (new EmailSender())->sendMail(self::RECEIVER_MAIL, self::SUBJECT_MAIL, self::CONTENT_MAIL);
        $this->assertEquals(true, $emailSenderSendMail);
    }

    public function testEmailSenderMailConfirmation(): void
    {
        $emailSenderMailConfirmation = (new EmailSender())->mailConfirmation(self::RECEIVER_MAIL, self::SUBJECT_MAIL, self::CONTENT_MAIL);
        $this->assertEquals("Le mail a été envoyé avec succès", $emailSenderMailConfirmation);
    }
}