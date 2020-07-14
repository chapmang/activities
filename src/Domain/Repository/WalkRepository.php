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

    public function findBySlug(string $slug): ?Walk
    {
         return $this->findOneBy(['slug' => $slug]);
    }

    public function recentModifiedWalk($max)
    {
        $qb = $this->getOrCreateQueryBuilder();
        return $this->selectBasicFields($qb)
            ->orderBy('w.modifiedDate', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    public function findAllByNameAndTagsQueryBuilder(?string $queryTerm, $tags = null): QueryBuilder
    {
        $qb = $this->getOrCreateQueryBuilder();
        $qb = $this->selectAllFields($qb);
        $qb = $this->addBasicSearch($qb, $queryTerm);
        $qb = $this->addTagUsed($qb, $tags);

        return $qb
            ->orderBy('w.name', 'DESC');
    }

    public function findAllByNameAndTags(?string $queryTerm, $tags = null)
    {

        $qb = $this->getOrCreateQueryBuilder();
        $qb = $this->selectBasicFields($qb);
        $qb = $this->addBasicSearch($qb, $queryTerm);
        $qb = $this->addTagUsed($qb, $tags);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    private function addTagUsed(QueryBuilder $qb, ?array $tags)
    {
        if ($tags && is_array($tags)) {
            foreach ($tags as $key => $value) {
                $qb->andWhere(':tag MEMBER OF w.tags')
                    ->setParameter('tag', $value->getID());
            }
        }
        return $qb;
    }

    private function addBasicSearch(QueryBuilder $qb, ?string $queryTerm)
    {
        return $qb->andWhere('w.name LIKE :term OR w.shortDescription LIKE :term OR w.description LIKE :term')
            ->setParameter('term', '%'.$queryTerm.'%');
    }

    private function selectBasicFields(QueryBuilder $qb)
    {
        return $qb->select('w.id, w.name, w.slug, w.status');
    }

    private function selectAllFields(QueryBuilder $qb)
    {
        return $qb->select('w');
    }
    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->_em->createQueryBuilder()
            ->from(Walk::class, 'w');
    }
}