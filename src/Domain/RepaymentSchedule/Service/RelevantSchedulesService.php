<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Service;

//Since defining what is 'relevant schedule', this is a domain concern
//Also it's not logic that belongs to our RepaymentSchedule aggregate, so Domain Service is best tool for this
use App\Domain\RepaymentSchedule\Interface\RepaymentScheduleRepositoryInterface;
use App\Domain\RepaymentSchedule\Model\RepaymentSchedule;

class RelevantSchedulesService
{
    public function __construct(
        private readonly RepaymentScheduleRepositoryInterface $repaymentScheduleRepository,
    ) {
    }

    /** @return RepaymentSchedule[] */
    public function getRelevantSchedules(bool $includeDeactivated = false): array
    {
        return $this->repaymentScheduleRepository->getFiltered(
            4,
            'total_interest_amount_value',
            false,
            $includeDeactivated
        );
    }
}
