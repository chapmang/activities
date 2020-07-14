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

    public function findBySlug(string $slug): ?Ride
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    public function recentModifiedRide($max)
    {
        $qb = $this->getOrCreateQueryBuilder();
        return $this->selectBasicFields($qb)
            ->orderBy('r.modifiedDate', 'DESC')
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
            ->orderBy('r.name', 'DESC');
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
                $qb->andWhere(':tag MEMBER OF r.tags')
                    ->setParameter('tag', $value->getID());
            }
        }
        return $qb;
    }

    private function addBasicSearch(QueryBuilder $qb, ?string $queryTerm)
    {
        return $qb->andWhere('r.name LIKE :term OR r.shortDescription LIKE :term OR r.description LIKE :term')
            ->setParameter('term', '%'.$queryTerm.'%');
    }

    private function selectBasicFields(QueryBuilder $qb)
    {
        return $qb->select('r.id, r.name, r.slug, r.status');
    }

    private function selectAllFields(QueryBuilder $qb)
    {
        return $qb->select('r');
    }
    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->_em->createQueryBuilder()
            ->from(Ride::class, 'r');
    }
}