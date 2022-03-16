<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DelayedOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DelayedOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method DelayedOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method DelayedOrder[]    findAll()
 * @method DelayedOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DelayedOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DelayedOrder::class);
    }

    /**
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     *
     * @return DelayedOrder[]
     */
    public function findByBetweenDates(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $qb = $this->createQueryBuilder('do');

        return $qb
            ->andWhere($qb->expr()->between('do.c', ':start', ':end'))
            ->setParameters(['start' => $start, 'end' => $end])
            ->getQuery()
            ->getResult()
            ;
    }
}
