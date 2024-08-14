<?php

namespace EmailSender\Infrastructure\Provider;

use EmailSender\Application\Service\SendMail\AbstractFactoryEmailProvider;
use EmailSender\Application\Service\SendMail\EmailApiInterface;
use EmailSender\Infrastructure\Provider\Brevo\BrevoSender;
use EmailSender\Infrastructure\Provider\Exceptions\InvalidProviderException;
use EmailSender\Tests\Infrastructure\Provider\ProviderFake;
use InvalidArgumentException;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FactoryEmailProvider extends AbstractFactoryEmailProvider
{
    public function __construct(private HttpClientInterface $client = new CurlHttpClient())
    {
    }
    public function buildProvider(string $provider): EmailApiInterface
    {
        switch ($provider) {
            case 'Brevo':
                $provider = new BrevoSender($this->client);
                break;
            case 'testProvider':
                $provider = new ProviderFake();
                break;
            default:
                throw new InvalidProviderException(
                    "Error : Invalid Provider provided",
                    InvalidProviderException::ERROR_CODE
                );
        }

        return $provider;
    }
}
