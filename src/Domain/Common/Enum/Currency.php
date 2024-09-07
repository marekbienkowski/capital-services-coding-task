<?php

declare(strict_types=1);

namespace App\Domain\Common\Enum;

enum Currency: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case PLN = 'PLN';
    //and the rest of currencies we'll be supporting :)
}
