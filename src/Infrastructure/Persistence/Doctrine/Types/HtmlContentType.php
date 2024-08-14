<?php

namespace EmailSender\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use EmailSender\Domain\Model\Mail\HtmlContent;

class HtmlContentType extends Type {
    public const TYPE_NAME = "htmlContent";

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @param HtmlContent $value
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
    public function convertToPHPValue($value, AbstractPlatform $platform): HtmlContent
    {
        return new HtmlContent($value);
    }
}