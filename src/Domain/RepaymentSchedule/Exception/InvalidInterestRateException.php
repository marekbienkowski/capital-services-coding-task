<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Exception;

use App\Domain\Common\Exception\DomainException;

class InvalidInterestRateException extends DomainException
{
    public static function forPercentagePoints(int $percentagePoints): self
    {
        return new self('%d is not valid loan percentage. Use one from the range <0, 100>');
    }
}
