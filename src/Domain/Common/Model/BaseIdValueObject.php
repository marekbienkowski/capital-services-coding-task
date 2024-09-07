<?php

declare(strict_types=1);

namespace App\Domain\Common\Model;

use App\Domain\Common\Interface\IdValueObjectInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

use function is_a;

abstract class BaseIdValueObject implements IdValueObjectInterface
{
    public function __construct(
        protected UuidInterface $uuid,
    ) {
    }

    public static function fromString(string $value): static
    {
        return new static(Uuid::fromString($value));
    }

    //Generating next identity may be also responsibility of a repository, yet I want simplicity for this task
    public static function next(): static
    {
        return new static(Uuid::uuid4());
    }

    public function equals(IdValueObjectInterface $other): bool
    {
        return
            is_a(static::class, $other::class, true)
            && $this->uuid->equals($other->uuid);
    }

    public function __toString()
    {
        return $this->uuid->toString();
    }
}
