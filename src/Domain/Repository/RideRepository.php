<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Ride;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * Class RideRepository
 * @package App\Domain\Repository
 */
class RideRepository extends ServiceEntityRepository implements RideRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ride::class);
    }

    public function add(Ride $ride): void
    {
        $this->_em->persist($ride);
    }

    public function remove(Ride $ride): void
    {
        $this->_em->remove($ride);
    }

    public function findById(int $id): ?Ride
    {
        return $this->find($id);
    }

    public function size(): int
    {
        $query = $this->getOrCreateQueryBuilder()
            ->select('count(r.id)')
            ->getQuery();
        return $query->getSingleScalarResult();
    }

    public function recentModifiedRide()
    {
        $qb = $this->getOrCreateQueryBuilder();
        return $qb->orderBy('r.modifiedDate', 'DESC')
            ->select('r.id, r.name, r.slug')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->_em->createQueryBuilder()
            ->from(Ride::class, 'r');
    }
}