<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ActivityRepository
 * @package App\Domain\Repository
 */
final class ActivityRepository extends ServiceEntityRepository implements ActivityRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function add(Activity $activity): void
    {
        $this->_em->persist($activity);
    }

    public function remove(Activity $activity): void
    {
        $this->_em->remove($activity);
    }

    public function findById(int $id): ?Activity
    {
        return $this->find($id);
    }

    public function findAllMapActivities()
    {
        $conn = $this->_em->getConnection();
        $sql = 'SELECT id, name, type as activityType, slug, point.ToString() AS point FROM activity';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();

//       $query = $this->_em->createQueryBuilder()
//           ->select('a.name', 'a.type')
//           ->from(Activity::class, 'a')
//           ->getQuery();
//        return $query->getResult();
    }

    public function size(): int
    {
        $query = $this->_em->createQueryBuilder()
            ->select('count(a.id)')
            ->from(Activity::class, 'a')
            ->getQuery();

        return $query->getSingleScalarResult();
    }

    public function getWithSearchQueryBuilder(?string $term): QueryBuilder
    {
        $qb = $this->getOrCreateQueryBuilder();

        if ($term) {
            $qb->andWhere('a.name LIKE :term OR a.description LIKE :term')
                ->setParameter('term', '%'.$term.'%');
        }

        return $qb
            ->orderBy('a.name', 'DESC');
    }

    public function getWithSearch(?string $term)
    {
        $query = $this->getOrCreateQueryBuilder()
            ->andWhere('a.name LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->getQuery();

        return $query->getResult();
    }

    public function findAllOpenOrderedByRecentUpdate()
    {
        $query = $this->addStatusIsOpenQueryBuilder()
            ->orderBy('a.modifiedDate', 'DESC')
            ->getQuery();

        return $query->getResult();
    }

    private function addStatusIsOpenQueryBuilder(QueryBuilder $qb = null)
    {

        return $this->getOrCreateQueryBuilder($qb)
            ->andWhere('a.status = 0');

    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->_em->createQueryBuilder()
            ->select('a')
            ->from(Activity::class, 'a');
    }
}