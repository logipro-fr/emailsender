<?php

namespace EmailSender\Tests\Infrastructure\Provider;

use EmailSender\Infrastructure\Provider\Brevo\BrevoSender;
use EmailSender\Infrastructure\Provider\Exceptions\InvalidProviderException;
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
    public function testAbastractFactoryException(): void {
        $this->expectException(InvalidProviderException::class);
        $this->expectExceptionCode(422);
        $this->expectExceptionMessage("Error : Invalid Provider provided");
        $mockHttp = new MockHttpClient();
        $providerApi = "test";
        (new ProviderFactoryEmailProvider($mockHttp))->buildProvider($providerApi);
    }
}
