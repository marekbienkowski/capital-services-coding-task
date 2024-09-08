<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Exception;

use App\Domain\Common\Exception\DomainException;
use Throwable;

use function sprintf;

class InvalidIdException extends DomainException
{
    /** @param scalar $value */
    public static function forValue(mixed $value, string $class, ?Throwable $previous = null): self
    {
        return new self(
            sprintf(
                'Value "%s" is not a valid %s',
                $value,
                basename(str_replace('\\', '/', $class))
            ),
            previous: $previous
        );
    }
}
