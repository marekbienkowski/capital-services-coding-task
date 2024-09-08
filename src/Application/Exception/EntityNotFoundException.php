<?php

declare(strict_types=1);

namespace App\Application\Exception;

use App\Domain\Common\Interface\IdValueObjectInterface;
use RuntimeException;
use Throwable;

class EntityNotFoundException extends RuntimeException
{
    public static function forId(IdValueObjectInterface $id, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('Entity with id "%s" could not be found', $id),
            previous: $previous,
        );
    }
}
