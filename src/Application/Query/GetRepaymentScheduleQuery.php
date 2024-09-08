<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Domain\RepaymentSchedule\Model\RepaymentScheduleId;

readonly class GetRepaymentScheduleQuery
{
    public function __construct(
        public RepaymentScheduleId $id,
    ) {
    }
}
