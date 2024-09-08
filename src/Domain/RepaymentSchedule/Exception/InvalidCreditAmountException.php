<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Exception;

use App\Domain\Common\Exception\DomainException;
use App\Domain\RepaymentSchedule\Model\Money;
use Throwable;

use function sprintf;

class InvalidCreditAmountException extends DomainException
{
    public static function forAmount(
        Money $amount,
        Money $minimum,
        Money $maximum,
        int $expectedDivisor,
        ?Throwable $previous = null,
    ): self {
        return new self(
            sprintf(
                'Credit amount %s is invalid. You should pick from range <%s, %s> and be divisible by %d',
                $amount,
                $minimum,
                $maximum,
                $expectedDivisor
            ),
            previous: $previous
        );
    }
}
