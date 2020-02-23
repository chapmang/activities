<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class ActivityRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function findAllOpenOrderedByRecentUpdate()
    {

        return $this->addStatusIsOpenQueryBuilder()
            ->orderBy('a.modifiedDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    private function addStatusIsOpenQueryBuilder(QueryBuilder $qb = null)
    {

        return $this->getOrCreateQueryBuilder($qb)
            ->andWhere('a.status = 0');

    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {

        return $qb ?: $this->createQueryBuilder('a');
    }
}