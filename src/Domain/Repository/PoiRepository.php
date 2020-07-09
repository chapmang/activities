<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Poi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * Class PoiRepository
 * @package App\Domain\Repository
 */
class PoiRepository extends ServiceEntityRepository implements PoiRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Poi::class);
    }

    public function add(Poi $poi): void
    {
        $this->_em->persist($poi);
    }

    public function remove(Poi $poi): void
    {
        $this->_em->remove($poi);
    }

    public function findById(int $id): ?Poi
    {
        return $this->find($id);
    }

    public function size(): int
    {
        $query = $this->getOrCreateQueryBuilder()
            ->select('count(p.id)')
            ->getQuery();
        return $query->getSingleScalarResult();
    }

    public function recentModifiedPoi()
    {
        $qb = $this->getOrCreateQueryBuilder();
        return $qb->orderBy('p.modifiedDate', 'DESC')
            ->select('p.id, p.name, p.slug')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->_em->createQueryBuilder()
            ->from(Poi::class, 'p');
    }
}