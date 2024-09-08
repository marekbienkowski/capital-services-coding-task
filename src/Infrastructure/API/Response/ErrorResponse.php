<?php

declare(strict_types=1);

namespace App\Infrastructure\API\Response;

class ErrorResponse
{
    public function __construct(
        public string $message,
    ) {
    }
}
