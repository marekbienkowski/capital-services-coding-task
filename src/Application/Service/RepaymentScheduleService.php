<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Command\CreateRepaymentScheduleCommand;
use App\Application\Command\DeactivateRepaymentScheduleCommand;
use App\Application\Exception\EntityNotFoundException;
use App\Application\Query\GetLatestRelevantSchedulesQuery;
use App\Application\Query\GetRepaymentScheduleQuery;
use App\Domain\RepaymentSchedule\Factory\RepaymentScheduleFactory;
use App\Domain\RepaymentSchedule\Interface\RepaymentScheduleRepositoryInterface;
use App\Domain\RepaymentSchedule\Model\RepaymentSchedule;
use App\Domain\RepaymentSchedule\Service\RelevantSchedulesService;

class RepaymentScheduleService
{
    //In real use case, I would probably create some command / query bus. However, for our example it's an overkill
    public function __construct(
        private readonly RepaymentScheduleRepositoryInterface $repaymentScheduleRepository,
        private readonly RepaymentScheduleFactory $scheduleFactory,
        private readonly RelevantSchedulesService $relevantSchedulesService,
    ) {
    }

    //Usually we follow CQS separation of concerns and commands does not return any data. However, in the task it was
    // explicitly stated, that we should return newly created data
    public function createRepaymentSchedule(CreateRepaymentScheduleCommand $command): RepaymentSchedule
    {
        $schedule = $this->scheduleFactory->create(
            scheduleType: $command->type,
            amount: $command->amount,
            installmentCount: $command->installmentCount,
        );

        //Make sure that schedule is persisted
        $this->repaymentScheduleRepository->add($schedule);

        return $schedule;
    }

    public function deactivateRepaymentSchedule(DeactivateRepaymentScheduleCommand $command): void
    {
        $schedule = $this->repaymentScheduleRepository->findByIdentity($command->id);

        if ($schedule === null) {
            throw EntityNotFoundException::forId($command->id);
        }

        $schedule->deactivate();

        $this->repaymentScheduleRepository->add($schedule);
    }

    //Also query handling should be separated from command handling, but in our example let's make it together, for
    // simplification
    public function getRepaymentSchedule(GetRepaymentScheduleQuery $query): RepaymentSchedule
    {
        $schedule = $this->repaymentScheduleRepository->findByIdentity($query->id);

        if ($schedule === null) {
            throw EntityNotFoundException::forId($query->id);
        }

        return $schedule;
    }

    /** @return RepaymentSchedule[] */
    public function getLatestRelevantSchedules(GetLatestRelevantSchedulesQuery $query): array
    {
        return $this->relevantSchedulesService->getRelevantSchedules(
            $query->includeDeactivated
        );
    }
}
