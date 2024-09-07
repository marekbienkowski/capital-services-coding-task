<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Model;

use DateTimeImmutable;

class Installment
{
    private RepaymentSchedule $repaymentSchedule;

    public function __construct(
        private InstallmentId $id,
        private Money $total,
        private Money $capital,
        private Money $interest,
        private DateTimeImmutable $date,
    ) {
    }

    public function getId(): InstallmentId
    {
        return $this->id;
    }

    public function getCapital(): Money
    {
        return $this->capital;
    }

    public function getInterest(): Money
    {
        return $this->interest;
    }

    public function getTotalAmount(): Money
    {
        return $this->total;
    }

    public function getPaymentDate(): DateTimeImmutable
    {
        return $this->date;
    }
}
