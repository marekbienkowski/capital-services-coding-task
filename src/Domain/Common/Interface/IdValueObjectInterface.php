<?php

declare(strict_types=1);

namespace App\Domain\Common\Interface;

use Stringable;

interface IdValueObjectInterface extends Stringable
{
    public static function fromString(string $value): static;

    public function equals(IdValueObjectInterface $other): bool;
}
