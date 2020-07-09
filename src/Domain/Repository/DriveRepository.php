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

    public function recentModifiedDrive()
    {
        $qb = $this->getOrCreateQueryBuilder();
        return $qb->orderBy('d.modifiedDate', 'DESC')
            ->select('d.id, d.name, d.slug')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->_em->createQueryBuilder()
            ->from(Drive::class, 'd');
    }
}