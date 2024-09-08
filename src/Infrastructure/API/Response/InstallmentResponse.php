<?php

declare(strict_types=1);

namespace App\Infrastructure\API\Response;

use App\Domain\RepaymentSchedule\Model\Installment;

readonly class InstallmentResponse
{
    public function __construct(
        public int $installmentNumber,
        public string $totalAmount,
        public string $capitalAmount,
        public string $interestAmount,
        public string $dateOfPayment,
    ) {
    }

    //Some mapping solution can be used, i.e. AutomapperPlus or some custom-made mappers. However, for such a simple
    // response in recruitment task, I assumed factory methods to be enough
    public static function fromEntity(Installment $installment): self
    {
        return new self(
            installmentNumber: $installment->getSequence(),
            totalAmount: (string) $installment->getTotal(),
            capitalAmount: (string) $installment->getCapital(),
            interestAmount: (string) $installment->getInterest(),
            dateOfPayment: $installment->getDate()->format('Y-m-d'),
        );
    }

    /**
     * @param iterable<Installment> $entities
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
