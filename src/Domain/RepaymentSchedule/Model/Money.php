<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Model;

use App\Domain\Common\Enum\Currency;
use App\Domain\RepaymentSchedule\Exception\InvalidCurrencyException;
use InvalidArgumentException;
use Stringable;
use ValueError;

use function intval;
use function strtoupper;

readonly class Money implements Stringable
{
    //When calculating money, you want to avoid precision loss as much as you can. Hence, money will be represented
    // internally as
    public function __construct(
        public int $value,
        public Currency $currency,
    ) {
    }

    public static function fromPrimitives(float $amount, string $currencySymbol): self
    {
        try {
            return self::fromFloat(
                amount: $amount,
                currency: Currency::from(strtoupper($currencySymbol)),
            );
        } catch (ValueError $exception) { //Domain should talk using domain objects, not some implementation details
            throw InvalidCurrencyException::fromCurrency($currencySymbol, $exception);
        }
    }

    public static function fromFloat(float $amount, Currency $currency): self
    {
        return new self(
            value: intval($amount * 100),
            currency: $currency
        );
    }

    public function equals(Money $other): bool
    {
        return $this->value === $other->value
            && $this->currency === $other->currency;
    }

    public function add(Money $other): Money
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Cannot add money in different currencies!');
        }

        return new Money($this->value + $other->value, $this->currency);
    }

    //that very simple implementation will cause precision loss, for instance with 1000 PLN credit and 3 installments
    //But I guess, for sake of this example it's ok. In business code it would be not
    //Anyway logic of dividing Money amount belongs to Money VO

    public function subtract(Money $other): Money
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Cannot add money in different currencies!');
        }

        return new Money($this->value - $other->value, $this->currency);
    }

    public function divide(int $divisor): Money
    {
        if ($divisor === 0) {
            throw new InvalidArgumentException('Cannot divide by zero!');
        }

        return new Money(intval($this->value / $divisor), $this->currency);
    }

    public function __toString(): string
    {
        return sprintf(
            '%s %s',
            number_format($this->value / 100, 2, '.', ' '),
            $this->currency->name
        );
    }
}
