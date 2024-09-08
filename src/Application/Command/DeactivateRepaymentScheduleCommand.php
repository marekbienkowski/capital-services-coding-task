<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\RepaymentSchedule\Model\RepaymentScheduleId;

readonly class DeactivateRepaymentScheduleCommand
{
    public function __construct(
        public RepaymentScheduleId $repaymentScheduleId,
    ) {
    }
}
