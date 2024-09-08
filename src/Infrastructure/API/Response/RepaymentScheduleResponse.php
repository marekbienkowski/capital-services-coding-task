<?php

declare(strict_types=1);

namespace App\Infrastructure\API\Response;

use App\Domain\RepaymentSchedule\Model\RepaymentSchedule;
use DateTime;

readonly class RepaymentScheduleResponse
{
    /** @param InstallmentResponse[] $schedule */
    public function __construct(
        public string $id,
        public string $type,
        public string $createdDate,
        public string $amount,
        public string $interestRate,
        public array $schedule,
    ) {
    }

    public static function fromEntity(RepaymentSchedule $repaymentSchedule): self
    {
        return new self(
            id: (string) $repaymentSchedule->getId(),
            type: $repaymentSchedule->getType()->value,
            createdDate: $repaymentSchedule->getCreatedAt()->format(DateTime::ATOM),
            amount: (string) $repaymentSchedule->getTotalAmount(),
            interestRate: (string) $repaymentSchedule->getInterestRate(),
            schedule: InstallmentResponse::fromEntityCollection($repaymentSchedule->getInstallments())
        );
    }

    /**
     * @param iterable<RepaymentSchedule> $entities
     *
     * @return self[]
     */
    public static function fromEntityCollection(iterable $entities): array
    {
        /** @var self[] $output */
        $output = [];

        foreach ($entities as $installment) {
            $output[] = self::fromEntity($installment);
        }

        return $output;
    }
}
