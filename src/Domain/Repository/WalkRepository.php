<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Walk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * Class WalkRepository
 * @package App\Domain\Repository
 */
final class WalkRepository extends ServiceEntityRepository implements WalkRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Walk::class);
    }

    public function add(Walk $walk): void
    {
        $this->_em->persist($walk);
    }

    public function remove(Walk $walk): void
    {
        $this->_em->remove($walk);
    }

    public function findById(int $id): ?Walk
    {
        return $this->find($id);
    }

    public function size(): int
    {
        $query = $this->_em->createQueryBuilder()
            ->select('count(w.id)')
            ->from(Walk::class, 'w')
            ->getQuery();

        return $query->getSingleScalarResult();
    }

    public function findBySlug(string $slug)
    {
         return $this->findOneBy(['slug' => $slug]);
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->_em->createQueryBuilder()
            ->select('w')
            ->from(Walk::class, 'w');
    }


}