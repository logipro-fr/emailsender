<?php

namespace EmailSender\Tests\Infrastructure\Persistence\Mail;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use EmailSender\Infrastructure\Persistence\Mail\EmailSenderRepositoryDoctrine;

class EmailSenderRepositoryDoctrineTest extends EmailSenderRepositoryTestBase
{
    use DoctrineRepositoryTesterTrait;

    protected function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(['mails']);
        $this->repository = new EmailSenderRepositoryDoctrine($this->getEntityManager());
    }
}
