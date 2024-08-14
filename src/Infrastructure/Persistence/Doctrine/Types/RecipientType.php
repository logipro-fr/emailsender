<?php
namespace EmailSender\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use EmailSender\Domain\Model\Mail\Contact;
use EmailSender\Domain\Model\Mail\Recipient;

class RecipientType extends Type {
    public const TYPE_NAME = "recipient";

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    /** 
     * @param Recipient $value
     * @param AbstractPlatform $platform
     * @return string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        $recipientsData = array_map(function (Contact $contact) {
            return $contact->getContactData();
        }, $value->getRecipients());

        return json_encode($recipientsData);
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     * @return Recipient
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Recipient
    {
        $data = json_decode($value, true);

        $contacts = array_map(function (array $contactData) {
            return new Contact($contactData['name'], $contactData['email']);
        }, $data);

        return new Recipient($contacts);
    }

    /**
     * 
     * @param array $column
     * @param AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'TEXT';
    }
}
