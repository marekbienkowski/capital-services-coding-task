<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Interface;

use App\Domain\RepaymentSchedule\Model\RepaymentSchedule;
use App\Domain\RepaymentSchedule\Model\RepaymentScheduleId;

interface RepaymentScheduleRepositoryInterface
{
    public function add(RepaymentSchedule $repaymentSchedule): void;

    public function findByIdentity(RepaymentScheduleId $identity): ?RepaymentSchedule;

    /** @return RepaymentSchedule[] */
    public function getAll(): array;
}
