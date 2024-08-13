<?php

namespace EmailSender\Domain\Model\Mail;

use EmailSender\Application\Service\SendMail\Exceptions\MalformedAddressException;
use InvalidArgumentException;

class Contact
{
    private string $name;
    private string $address;

    public function __construct(string $name, string $address)
    {
        if (empty($name)) {
            throw new InvalidArgumentException("Contact name cannot be empty");
        }

        if (empty($address)) {
            throw new InvalidArgumentException("Contact address cannot be empty");
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
        if (!$this->isValidEmailAddress($this->address)) {
            throw new MalformedAddressException("Invalid email address: $this->address");
        }
        return $this->address;
    }
}
