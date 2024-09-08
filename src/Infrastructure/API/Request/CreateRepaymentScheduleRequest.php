<?php

declare(strict_types=1);

namespace App\Infrastructure\API\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateRepaymentScheduleRequest
{
    public function __construct(
        #[Assert\Type('int')]
        #[Assert\Positive]
        public int $amount,
        #[Assert\NotBlank]
        public string $currency,
        #[Assert\Positive]
        public int $installmentCount,
    ) {
    }
}
