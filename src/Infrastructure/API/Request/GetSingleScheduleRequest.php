<?php

declare(strict_types=1);

namespace App\Infrastructure\API\Request;

use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Validator\Constraints as Assert;

readonly class GetSingleScheduleRequest
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\Uuid]
        #[MapQueryParameter('id')]
        public string $id,
    ) {
    }
}
