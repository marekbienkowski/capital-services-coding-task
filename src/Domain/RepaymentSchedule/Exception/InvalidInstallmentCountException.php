<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Exception;

use App\Domain\Common\Exception\DomainException;
use Throwable;

class InvalidInstallmentCountException extends DomainException
{
    public static function forDivisionCount(
        int $divisionCount,
        int $minimum,
        int $maximum,
        int $expectedDivisor,
        ?Throwable $previous = null,
    ): self {
        return new self(
            sprintf(
                'Invalid division count: %d. It must be within <%d,%d> range and be divisible by %d',
                $divisionCount,
                $minimum,
                $maximum,
                $expectedDivisor
            ),
            previous: $previous,
        );
    }
}
