<?php

declare(strict_types=1);

namespace App\Application\Query;

readonly class GetLatestRelevantSchedulesQuery
{
    public function __construct(
        public bool $includeDeactivated,
    ) {
    }
}
