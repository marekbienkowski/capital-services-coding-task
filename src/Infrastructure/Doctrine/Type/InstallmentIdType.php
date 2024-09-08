<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\RepaymentSchedule\Model\InstallmentId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class InstallmentIdType extends GuidType
{
    const string INSTALLMENT_ID = 'installment_id';

    /** @param string $value */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): InstallmentId
    {
        return InstallmentId::fromString($value);
    }

    /** @param string $value */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        return (string) $value;
    }

    public function getName(): string
    {
        return self::INSTALLMENT_ID;
    }
}
