<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Model;

use App\Domain\RepaymentSchedule\Exception\InvalidInterestRateException;

class InterestRate
{
    public function __construct(
        private int $percentagePoints,
    ) {
        $this->validate();
    }

    public function asPercentagePoints(): int
    {
        return $this->percentagePoints;
    }

    public function asFraction(): float
    {
        return $this->percentagePoints / 100;
    }

    private function validate(): void
    {
        if ($this->percentagePoints < 0 || $this->percentagePoints > 100) {
            throw InvalidInterestRateException::forPercentagePoints($this->percentagePoints);
        }
    }
}
