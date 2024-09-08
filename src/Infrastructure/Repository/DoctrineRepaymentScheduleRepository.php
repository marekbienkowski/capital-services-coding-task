<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\RepaymentSchedule\Interface\RepaymentScheduleRepositoryInterface;
use App\Domain\RepaymentSchedule\Model\RepaymentSchedule;
use App\Domain\RepaymentSchedule\Model\RepaymentScheduleId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;

/** @extends ServiceEntityRepository<RepaymentSchedule> */
class DoctrineRepaymentScheduleRepository extends ServiceEntityRepository implements
    RepaymentScheduleRepositoryInterface
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
        /** @var array<string, scalar> $criteria */
        $criteria = [];

        if (!$includeDeactivated) {
            $criteria['isActive'] = true;
        }

        /** @var array<string, 'ASC'|'asc'|'desc'|'DESC'> $sorting */
        $sorting = [$sortBy => $sortDescending ? 'DESC' : 'ASC'];

        /** @var RepaymentSchedule[] $matchingEntities */
        $matchingEntities = $this->findBy(
            criteria: $criteria,
            orderBy: $sorting,
            limit: $limit,
        );

        return $matchingEntities;
    }
}
