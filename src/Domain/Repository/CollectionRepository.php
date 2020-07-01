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

    public function findAllWithSearch(?string $searchTerm)
    {
        return $this->addFindAllWithSearchQueryBuilder($searchTerm)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
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
    
    private function addFindAllWithSearchQueryBuilder(?string $searchTerm, QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)
            ->andWhere('c.name LIKE :term')
            ->setParameter('term', '%'.$searchTerm.'%');
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->createQueryBuilder('c');
    }


}