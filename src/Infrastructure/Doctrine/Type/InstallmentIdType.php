<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\RepaymentSchedule\Model\InstallmentId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Ramsey\Uuid\UuidInterface;

class InstallmentIdType extends GuidType
{
    const INSTALLMENT_ID = 'installment_id';

    /** @param UuidInterface $value */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): InstallmentId
    {
        return new InstallmentId($value);
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
