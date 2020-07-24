<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * Class CollectionRepository
 * @package App\Domain\Repository
 */
class CollectionRepository extends ServiceEntityRepository implements CollectionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collection::class);
    }

    public function add(Collection $collection): void
    {
        $this->_em->persist($collection);
    }

    public function remove(Collection $collection): void
    {
        $this->_em->remove($collection);
    }

    public function findById(int $id): ?Collection
    {
        return $this->find($id);
    }

    public function size(): int
    {
        $query = $this->_em->createQueryBuilder()
            ->select('count(c.id)')
            ->from(Collection::class, 'c')
            ->getQuery();

        return $query->getSingleScalarResult();
    }

    public function findBySlug(string $slug)
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    public function findAll()
    {
        return $this->findBy(array(), array('name' => 'ASC'));
    }

    public function searchActivitiesNotInCollection($searchCollection, $searchTerm)
    {
        // Build the DQL for the sub-query
        $sub = $this->_em->createQueryBuilder();
        $sub->select('IDENTITY(cc.activity)')
            ->from('Domain:CollectionContents', 'cc')
            ->andWhere('cc.collection = :collectionID');


        // Build the fully nested query
        $qb = $this->_em->createQueryBuilder();
        $qb->select('act')
            ->from('Domain:Activity', 'act')
            ->andWhere('act.name LIKE :name')
            ->andWhere($qb->expr()->notIn('act.id', $sub->getDQL()));

        $qb->setParameter('collectionID', $searchCollection)
            ->setParameter('name', '%'.$searchTerm.'%')
            ->orderBy('act.name', 'ASC');

        $query = $qb->getQuery();

        return $query->getResult();

    }

    public function recentModifiedCollection($max)
    {
        $qb = $this->getOrCreateQueryBuilder();
        return $this->selectBasicFields($qb)
            ->orderBy('c.modifiedDate', 'DESC')
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
            ->orderBy('c.name', 'DESC');
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
                $qb->andWhere(':tag MEMBER OF c.tags')
                    ->setParameter('tag', $value->getID());
            }
        }
        return $qb;
    }

    private function addBasicSearch(QueryBuilder $qb, ?string $queryTerm)
    {
        return $qb->andWhere('c.name LIKE :term OR c.description LIKE :term')
            ->setParameter('term', '%'.$queryTerm.'%');
    }

    private function selectBasicFields(QueryBuilder $qb)
    {
        return $qb->select('c.id, c.name, c.slug, c.status');
    }

    private function selectAllFields(QueryBuilder $qb)
    {
        return $qb->select('c');
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->createQueryBuilder('c');
    }


}