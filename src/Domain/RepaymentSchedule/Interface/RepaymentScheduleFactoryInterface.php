<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Interface;

use App\Domain\RepaymentSchedule\Enum\ScheduleType;
use App\Domain\RepaymentSchedule\Model\Money;
use App\Domain\RepaymentSchedule\Model\RepaymentSchedule;

interface RepaymentScheduleFactoryInterface
{
    public function create(
        ScheduleType $scheduleType,
        Money $amount,
        int $installmentCount,
    ): RepaymentSchedule;
}
