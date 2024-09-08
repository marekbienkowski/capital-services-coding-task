<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\RepaymentSchedule\Interface\RepaymentScheduleRepositoryInterface;
use App\Domain\RepaymentSchedule\Model\RepaymentSchedule;
use App\Domain\RepaymentSchedule\Model\RepaymentScheduleId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<RepaymentSchedule> */
class DoctrineRepaymentScheduleRepository extends ServiceEntityRepository implements
    RepaymentScheduleRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RepaymentSchedule::class);
    }

    public function add(RepaymentSchedule $repaymentSchedule): void
    {
        $this->getEntityManager()->persist($repaymentSchedule);
        $this->getEntityManager()->flush();
    }

    public function findByIdentity(RepaymentScheduleId $identity): ?RepaymentSchedule
    {
        /** @var ?RepaymentSchedule $entity */
        $entity = $this->find(
            $identity->__toString()
        );

        return $entity;
    }

    /** @return RepaymentSchedule[] */
    public function getFiltered(
        int $limit,
        string $sortBy,
        bool $sortDescending = false,
        bool $includeDeactivated = false,
    ): array {
        $queryBuilder = $this->createQueryBuilder('r')
            ->orderBy(
                'r.totalInterestAmount.value',
                $sortDescending ? 'DESC' : 'ASC'
            )
            ->setMaxResults(4)
        ;
        if (!$includeDeactivated) {
            $queryBuilder->where('r.isActive = :isActive')
                ->setParameter('isActive', true)
            ;
        }

        /** @var RepaymentSchedule[] $matchingSchedules */
        $matchingSchedules = $queryBuilder->getQuery()->getResult();

        return $matchingSchedules;
    }
}
