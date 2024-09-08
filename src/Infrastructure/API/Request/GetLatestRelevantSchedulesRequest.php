<?php

declare(strict_types=1);

namespace App\Infrastructure\API\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class GetLatestRelevantSchedulesRequest
{
    public function __construct(
        #[Assert\Type('bool')]
        public bool $excludeDeactivated = false,
    ) {
    }
}
