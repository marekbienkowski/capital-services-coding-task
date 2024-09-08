<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Model;

use App\Domain\RepaymentSchedule\Exception\InvalidInterestRateException;
use Stringable;

class InterestRate implements Stringable
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

    public function __toString(): string
    {
        return sprintf('%d%%', $this->percentagePoints);
    }

    private function validate(): void
    {
        if ($this->percentagePoints < 0 || $this->percentagePoints > 100) {
            throw InvalidInterestRateException::forPercentagePoints($this->percentagePoints);
        }
    }
}
