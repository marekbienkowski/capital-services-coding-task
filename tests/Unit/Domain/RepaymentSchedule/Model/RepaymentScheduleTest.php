<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\RepaymentSchedule\Model;

use App\Domain\Common\Enum\Currency;
use App\Domain\RepaymentSchedule\Enum\ScheduleType;
use App\Domain\RepaymentSchedule\Exception\InvalidAmountException;
use App\Domain\RepaymentSchedule\Exception\InvalidInstallmentCountException;
use App\Domain\RepaymentSchedule\Model\InterestRate;
use App\Domain\RepaymentSchedule\Model\Money;
use App\Domain\RepaymentSchedule\Model\RepaymentSchedule;
use App\Domain\RepaymentSchedule\Model\RepaymentScheduleId;
use DateTime;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class RepaymentScheduleTest extends TestCase
{
    #[Test]
    public function repaymentScheduleIsProperlyCreated(): void
    {
        $amount = new Money(1000000, Currency::PLN);
        $installmentCount = 3;
        $interestRate = new InterestRate(5);

        $testedObject = new RepaymentSchedule(
            id: RepaymentScheduleId::next(),
            totalAmount: $amount,
            installmentCount: $installmentCount,
            interestRate: $interestRate,
            type: ScheduleType::ConstantInstallment,
            isActive: true,
        );

        $this->assertCount($installmentCount, $testedObject->getInstallments());
        //let's use floats for expectations, to make test more readable
        $this->assertTrue(
            $testedObject->getTotalInterest()->equals(Money::fromFloat(83.43, Currency::PLN))
        );

        $expectedInstallmentDateStart = new DateTime();

        foreach ($testedObject->getInstallments() as $installment) {
            $this->assertTrue(
                $installment->getTotalAmount()->equals(Money::fromFloat(3361.14, Currency::PLN)),
            );

            $this->assertTrue(
                $installment->getCapital()->equals(Money::fromFloat(3333.33, Currency::PLN)),
            );

            $this->assertTrue(
                $installment->getInterest()->equals(Money::fromFloat(27.81, Currency::PLN)),
            );

            $this->assertSame('10', $installment->getPaymentDate()->format('d'));
            $this->assertSame(
                $expectedInstallmentDateStart->modify('+1 month')->format('m'),
                $installment->getPaymentDate()->format('m')
            );
        }
    }

    #[Test]
    #[TestWith([100.00])] //too little
    #[TestWith([20000.00])] //too much
    #[TestWith([1300.00])] //non divisible
    public function scheduleWillNotAllowToBeCreatedWithInvalidAmount(
        float $amount,
    ): void {
        $this->expectException(InvalidAmountException::class);

        $testedObject = new RepaymentSchedule(
            id: RepaymentScheduleId::next(),
            totalAmount: Money::fromFloat($amount, Currency::PLN),
            installmentCount: 3,
            interestRate: new InterestRate(5),
            type: ScheduleType::ConstantInstallment,
            isActive: true,
        );
    }

    #[Test]
    #[TestWith([2])] //too little
    #[TestWith([20])] //too much
    #[TestWith([4])] //non divisible
    public function scheduleWillNotAllowToBeCreatedWithInvalidInstallmentCount(
        int $installmentCount,
    ): void {
        $this->expectException(InvalidInstallmentCountException::class);

        $testedObject = new RepaymentSchedule(
            id: RepaymentScheduleId::next(),
            totalAmount: Money::fromFloat(1000.00, Currency::PLN),
            installmentCount: $installmentCount,
            interestRate: new InterestRate(5),
            type: ScheduleType::ConstantInstallment,
            isActive: true,
        );
    }
}
