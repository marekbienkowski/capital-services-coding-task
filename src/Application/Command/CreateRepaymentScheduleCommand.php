<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\RepaymentSchedule\Model\Money;

readonly class CreateRepaymentScheduleCommand
{
    public function __construct(
        public Money $amount,
        public int $installmentCount,
    ) {
    }
}
