<?php

namespace EmailSender\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use EmailSender\Domain\Model\Mail\Contact;
use EmailSender\Domain\Model\Mail\Sender;

class SenderType extends Type {
    public const TYPE_NAME = "sender";

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @param Sender $value
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value->__toString();
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return 'text';
    }

    /**
     * @param string $value
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): Sender
    {
        $array = explode(" ",$value);
        return new Sender(new Contact($array[0], $array[1]));
    }
}