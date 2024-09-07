<?php

declare(strict_types=1);

namespace App\API\Request;

readonly class CreateRepaymentScheduleRequest
{
    public function __construct(
        public int $amount,
        public string $currency,
        public int $divisionCount,
    ) {
    }
}
