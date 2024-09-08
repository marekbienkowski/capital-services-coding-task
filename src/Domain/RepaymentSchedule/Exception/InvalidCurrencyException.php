<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Exception;

use App\Domain\Common\Exception\DomainException;
use Throwable;

class InvalidCurrencyException extends DomainException
{
    public static function fromCurrency(string $currency, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('%s is not recognized as supported currency', $currency),
            previous: $previous
        );
    }
}
