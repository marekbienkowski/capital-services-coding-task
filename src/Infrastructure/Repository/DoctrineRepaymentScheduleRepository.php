<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\RepaymentSchedule\Interface\RepaymentScheduleRepositoryInterface;
use App\Domain\RepaymentSchedule\Model\RepaymentSchedule;
use App\Domain\RepaymentSchedule\Model\RepaymentScheduleId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;

class DoctrineRepaymentScheduleRepository extends ServiceEntityRepository
    implements RepaymentScheduleRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RepaymentSchedule::class);
    }

    public function nextIdentity(): RepaymentScheduleId
    {
        return new RepaymentScheduleId(Uuid::uuid4());
    }

    public function add(RepaymentSchedule $repaymentSchedule): void
    {
        $this->getEntityManager()->persist($repaymentSchedule);
    }

    public function findByIdentity(RepaymentScheduleId $identity): ?RepaymentSchedule
    {
        return $this->find(
            $identity->__toString()
        );
    }

    public function getAll(): array
    {
        return $this->findAll();
    }
}
