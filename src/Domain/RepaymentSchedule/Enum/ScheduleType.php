<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Enum;

enum ScheduleType: string
{
    case ConstantInstallment = 'ConstantInstallment';
    //and any others that may be added to the system
}
