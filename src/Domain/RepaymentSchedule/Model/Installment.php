<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Model;

use DateTimeImmutable;

class Installment
{
    public function __construct(
        private InstallmentId $id,
        private Money $total,
        private Money $capital,
        private Money $interest,
        private DateTimeImmutable $date,
        private int $sequence,
        private RepaymentSchedule $repaymentSchedule,
    ) {
    }

    public function getTotal(): Money
    {
        return $this->total;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getSequence(): int
    {
        return $this->sequence;
    }

    public function getRepaymentSchedule(): RepaymentSchedule
    {
        return $this->repaymentSchedule;
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
