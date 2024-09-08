<?php

declare(strict_types=1);

namespace App\Domain\RepaymentSchedule\Model;

use App\Domain\RepaymentSchedule\Enum\ScheduleType;
use App\Domain\RepaymentSchedule\Exception\InvalidCreditAmountException;
use App\Domain\RepaymentSchedule\Exception\InvalidInstallmentCountException;
use App\Domain\RepaymentSchedule\Exception\InvalidStateException;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use function intval;

class RepaymentSchedule
{
    //In real world we should consider own implementation of collection to prevent persistence details leakage to domain
    /** @var Collection<int, Installment> */
    private Collection $installments;

    //we DO NOT want to aggregate and calculate this amount for every query, so let's make advantage from storage
    // being cheap :)
    private Money $totalInterestAmount;
    private DateTimeImmutable $createdAt;

    public function __construct(
        private RepaymentScheduleId $id,
        private Money $totalAmount,
        private int $installmentCount,
        private InterestRate $interestRate,
        private ScheduleType $type,
        private bool $isActive = true,
    ) {
        //Domain must prevent setting it into invalid state, so validation logic belongs to the aggregate
        //If it will be too big, it can be delegated to some validator class
        $this->validateAmount();
        $this->validateInstallmentCount();

        $this->installments = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();

        $this->calculateInstallments();
        $this->calculateTotalInterest();
    }

    public function getTotalInterestAmount(): Money
    {
        return $this->totalInterestAmount;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getInterestRate(): InterestRate
    {
        return $this->interestRate;
    }

    public function getId(): RepaymentScheduleId
    {
        return $this->id;
    }

    public function getType(): ScheduleType
    {
        return $this->type;
    }

    public function getTotalAmount(): Money
    {
        return $this->totalAmount;
    }

    public function getInstallmentCount(): int
    {
        return $this->installmentCount;
    }

    public function getTotalInterest(): Money
    {
        return $this->totalInterestAmount;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    /** @return Collection<int, Installment> */
    public function getInstallments(): Collection
    {
        return $this->installments;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new InvalidStateException('Cannot activate active repayment schedule.');
        }

        $this->isActive = true;
    }

    public function deactivate(): void
    {
        if (!$this->isActive()) {
            throw new InvalidStateException('Cannot deactivate non-active repayment schedule.');
        }

        $this->isActive = false;
    }

    private function calculateInstallments(): void
    {
        $totalAmount = $this->totalAmount->value;
        $numberOfInstallmentsInYear = 12;
        $interestRatePerMonth = $this->interestRate->asFraction() / $numberOfInstallmentsInYear;

        $pow_factor = pow(1 + $interestRatePerMonth, $this->installmentCount);

        //here's our total installment amount, as specified in the task
        $totalInstallmentAmount = new Money(
            intval($totalAmount * ($interestRatePerMonth * $pow_factor) / ($pow_factor - 1)),
            $this->totalAmount->currency
        );

        //now let's calculate capital and interest, by dividing total amount per number of installments, to get capital
        //amount. Result of substraction of total installment amount and capital would be our interest.
        //Simple implementation created in this task WILL cause some precision loss, but I think it's enough for the
        //recruitment task
        $installmentCapital = $this->totalAmount->divide($this->installmentCount);
        $installmentInterest = $totalInstallmentAmount->subtract($installmentCapital);

        $dateOfInstallment = $this->getDateOfFirstInstallment();

        for ($i = 1; $i <= $this->installmentCount; $i++) {
            if ($i !== 0) {
                $dateOfInstallment = $dateOfInstallment->modify('+1 month');
            }

            $this->installments->add(
                new Installment(
                    id: InstallmentId::next(),
                    total: $totalInstallmentAmount,
                    capital: $installmentCapital,
                    interest: $installmentInterest,
                    date: $dateOfInstallment,
                    sequence: $i
                )
            );
        }
    }

    private function calculateTotalInterest(): void
    {
        $totalInterest = new Money(0, $this->totalAmount->currency);

        foreach ($this->installments as $installment) {
            $totalInterest = $totalInterest->add($installment->getInterest());
        }

        $this->totalInterestAmount = $totalInterest;
    }

    //Just an example. Let's assume, that first installment will be paid at 10th day of next month
    private function getDateOfFirstInstallment(): DateTimeImmutable
    {
        $date = new DateTimeImmutable('today');
        $dateNextMonth = $date->modify('+1 month');

        return $dateNextMonth->setDate(
            (int) $dateNextMonth->format('Y'),
            (int) $dateNextMonth->format('m'),
            10
        );
    }

    private function validateInstallmentCount(): void
    {
        $min = 3;
        $max = 18;
        $expectedDivisor = 3;

        //that's somehow an inversion of how if clause should be constructed, however I find it less error prone to
        // write  validation rules as 'what should be' (is valid)
        if (
            $this->installmentCount >= $min
            && $this->installmentCount <= $max
            && $this->installmentCount % $expectedDivisor === 0
        ) {
            return;
        }

        throw InvalidInstallmentCountException::forDivisionCount($this->installmentCount, $min, $max, $expectedDivisor);
    }

    private function validateAmount(): void
    {
        $min = 100000;
        $max = 1200000;
        $expectedDivisor = 50000;

        //We may support different amounts for different currencies, but for our example let's go with simple version
        //For such situation, entity should receive some rules object via some factory
        if (
            $this->totalAmount->value >= $min
            && $this->totalAmount->value <= $max
            && $this->totalAmount->value % $expectedDivisor === 0
        ) {
            return;
        }

        throw InvalidCreditAmountException::forAmount(
            $this->totalAmount,
            new Money($min, $this->totalAmount->currency),
            new Money($max, $this->totalAmount->currency),
            $expectedDivisor / 100
        );
    }
}
