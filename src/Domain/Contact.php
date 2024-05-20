<?php

namespace EmailSender\Domain;

use EmailSender\Application\Service\SendMail\Exceptions\MalformedAddressException;

class Contact
{
    private string $name;
    private string $address;

    public function __construct(string $name, string $address)
    {

        if (!$this->isValidEmailAddress($address)) {
            throw new MalformedAddressException("Invalid email address: $address");
        }

        $this->name = $name;
        $this->address = $address;
    }

    private function isValidEmailAddress(string $address): mixed
    {
        return filter_var($address, FILTER_VALIDATE_EMAIL);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}
