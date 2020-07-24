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

    public function findBySlug(string $slug): ?Poi
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    public function recentModifiedPoi(int $max)
    {
        $qb = $this->getOrCreateQueryBuilder();
        return $this->selectBasicFields($qb)
            ->orderBy('p.modifiedDate', 'DESC')
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
            ->orderBy('p.name', 'DESC');
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
                $qb->andWhere(':tag MEMBER OF p.tags')
                    ->setParameter('tag', $value->getID());
            }
        }
        return $qb;
    }

    private function addBasicSearch(QueryBuilder $qb, ?string $queryTerm)
    {
        return $qb->andWhere('p.name LIKE :term OR p.shortDescription LIKE :term OR p.description LIKE :term')
            ->setParameter('term', '%'.$queryTerm.'%');
    }

    private function selectBasicFields(QueryBuilder $qb)
    {
        return $qb->select('p.id, p.name, p.slug, p.status');
    }

    private function selectAllFields(QueryBuilder $qb)
    {
        return $qb->select('p');
    }
    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->_em->createQueryBuilder()
            ->from(Poi::class, 'p');
    }
}