<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Factory;

use App\Domain\RepaymentSchedule\Enum\ScheduleType;
use App\Domain\RepaymentSchedule\Interface\RepaymentScheduleFactoryInterface;
use App\Domain\RepaymentSchedule\Model\InterestRate;
use App\Domain\RepaymentSchedule\Model\Money;
use App\Domain\RepaymentSchedule\Model\RepaymentSchedule;
use App\Domain\RepaymentSchedule\Model\RepaymentScheduleId;

class RepaymentScheduleFactory implements RepaymentScheduleFactoryInterface
{
    public function create(
        ScheduleType $scheduleType,
        Money $amount,
        int $installmentCount,
    ): RepaymentSchedule {
        return new RepaymentSchedule(
            id: RepaymentScheduleId::next(),
            totalAmount: $amount,
            installmentCount: $installmentCount,
            interestRate: $this->getInterestRate(),
            type: ScheduleType::ConstantInstallment,
            isActive: true
        );
    }

    //For simplification of example, let's assume we'll have only one possible interest rate
    private function getInterestRate(): InterestRate
    {
        return new InterestRate(5);
    }
}
