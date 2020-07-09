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
        $query = $this->getOrCreateQueryBuilder()
            ->select('count(w.id)')
            ->getQuery();

        return $query->getSingleScalarResult();
    }

    public function findBySlug(string $slug)
    {
         return $this->findOneBy(['slug' => $slug]);
    }

    public function recentModifiedWalk()
    {
        $qb = $this->getOrCreateQueryBuilder();
        return $qb->orderBy('w.modifiedDate', 'DESC')
            ->select('w.id, w.name, w.slug, w.status')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

    }

    public function getWithSearch(?string $term): QueryBuilder
    {
        $qb = $this->getOrCreateQueryBuilder()
            ->select('w');

        if ($term) {
            $qb->andWhere('w.name LIKE :term OR w.description LIKE :term')
                ->setParameter('term', '%'.$term.'%');
        }

        return $qb
            ->orderBy('w.name', 'DESC');
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->_em->createQueryBuilder()
            ->from(Walk::class, 'w');
    }


}