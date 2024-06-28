<?php

namespace EmailSender\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Dotenv\Dotenv;

class WebBaseTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Chargez le Dotenv si ce n'est pas déjà fait dans votre application
        $dotenv = new Dotenv();
        $dotenv->usePutenv(); // Assurez-vous que putenv est utilisé
        $dotenv->load(__DIR__ . '/../.env.test');
    }
}
