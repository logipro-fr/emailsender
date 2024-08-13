<?php

namespace EmailSender\Tests\Infrastructure\Provider;

use EmailSender\Infrastructure\Provider\Brevo\BrevoSender;
use EmailSender\Infrastructure\Provider\FactoryEmailProvider as ProviderFactoryEmailProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class FactoryEmailProviderTest extends TestCase
{
    public function testAbstractFactoryBrevo(): void
    {
        $mockHttp = new MockHttpClient();
        $providerApi = "Brevo";
        $sut = (new ProviderFactoryEmailProvider($mockHttp))->buildProvider($providerApi);
        $this->assertInstanceOf(BrevoSender::class, $sut);
    }

    public function testAbstractFacotoryTestProvider(): void
    {
        $mockHttp = new MockHttpClient();
        $providerApi = "testProvider";
        $sut = (new ProviderFactoryEmailProvider($mockHttp))->buildProvider($providerApi);
        $this->assertInstanceOf(ProviderFake::class, $sut);
    }
}
