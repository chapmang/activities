<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Application\GeoConversion\GeoConverter;
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

    /**
     * @var GeoConverter
     */
    private $geconverter;

    public function __construct(ManagerRegistry $registry, GeoConverter $geoConverter)
    {
        parent::__construct($registry, Activity::class);
        $this->geconverter = $geoConverter;
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
        $sql = 'SELECT id, name, type as activityType, slug, short_description as shortDescription, point.ToString() AS point FROM activity';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();

        for ($i = 0; $i < count($results); ++$i) {
            foreach ($results[$i] as $key => $value) {
                if ($key == 'point') {

                    $results[$i]['point'] = $this->geconverter->wkt_to_geom($value);
                }
            }
        }
        return $results;

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
            $qb->andWhere('a.name LIKE :term OR a.shortDescription LIKE :term')
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