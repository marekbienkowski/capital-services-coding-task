<?php

declare(strict_types=1);

namespace App\Infrastructure\API\Request;

readonly class GetLatestRelevantSchedulesRequest
{
    public function __construct(
        public bool $excludeDeactivated = false,
    ) {
    }
}
