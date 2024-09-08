<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\RepaymentSchedule\Model\RepaymentScheduleId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class RepaymentScheduleIdType extends GuidType
{
    const string REPAYMENT_SCHEDULE_ID = 'repayment_schedule_id';

    /** @param string $value */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): RepaymentScheduleId
    {
        return RepaymentScheduleId::fromString($value);
    }

    /** @param RepaymentScheduleId $value */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        return (string) $value;
    }

    public function getName(): string
    {
        return self::REPAYMENT_SCHEDULE_ID;
    }
}
