<?php

declare(strict_types=1);

namespace App\Domain\Common\Interface;

use Ramsey\Uuid\UuidInterface;
use Stringable;

interface IdValueObjectInterface extends Stringable
{
    public static function fromString(string $value): static;

    public function getUuid(): UuidInterface;

    public function equals(IdValueObjectInterface $other): bool;
}
