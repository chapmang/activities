<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Drive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * Class DriveRepository
 * @package App\Domain\Repository
 */
class DriveRepository extends ServiceEntityRepository implements DriveRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Drive::class);
    }

    public function add(Drive $drive): void
    {
        $this->_em->persist($drive);
    }

    public function remove(Drive $drive): void
    {
        $this->_em->remove($drive);
    }

    public function findById(int $id): ?Drive
    {
        return $this->find($id);
    }

    public function size(): int
    {
        $query = $this->getOrCreateQueryBuilder()
            ->select('count(d.id)')
            ->getQuery();

        return $query->getSingleScalarResult();
    }

    public function findBySlug(string $slug): ?Drive
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    public function recentModifiedDrive($max)
    {
        $qb = $this->getOrCreateQueryBuilder();
        return $this->selectBasicFields($qb)
            ->orderBy('d.modifiedDate', 'DESC')
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
            ->orderBy('d.name', 'DESC');
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
                $qb->andWhere(':tag MEMBER OF d.tags')
                    ->setParameter('tag', $value->getID());
            }
        }
        return $qb;
    }

    private function addBasicSearch(QueryBuilder $qb, ?string $queryTerm)
    {
        return $qb->andWhere('d.name LIKE :term OR d.shortDescription LIKE :term OR d.description LIKE :term')
            ->setParameter('term', '%'.$queryTerm.'%');
    }

    private function selectBasicFields(QueryBuilder $qb)
    {
        return $qb->select('d.id, d.name, d.slug, d.status');
    }

    private function selectAllFields(QueryBuilder $qb)
    {
        return $qb->select('d');
    }
    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->_em->createQueryBuilder()
            ->from(Drive::class, 'd');
    }
}