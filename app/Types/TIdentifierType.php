<?php

namespace App\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class TIdentifierType extends Type
{
    public function getName(): string
    {
        return 'TIdentifier';
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        // Определите SQL-тип вашего кастомного типа данных
        return 'VARCHAR(255)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        // Реализуйте преобразование значения из базы данных в PHP
        return $value;
    }
}
