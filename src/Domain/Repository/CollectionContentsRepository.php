<?php

namespace App\Domain\Repository;

use App\Application\GeoConversion\GeoConverter;
use App\Domain\Entity\CollectionContents;
use App\Domain\Entity\Walk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\QueryBuilder;

class CollectionContentsRepository extends ServiceEntityRepository implements CollectionContentsRepositoryInterface
{
    /**
     * @var GeoConverter
     */
    private $geconverter;

    public function __construct(ManagerRegistry $registry, GeoConverter $geoConverter)
    {
        parent::__construct($registry, CollectionContents::class);
        $this->geconverter = $geoConverter;
    }

    public function add(CollectionContents $walk): void
    {
        $this->_em->persist($walk);
    }

    public function remove(CollectionContents $walk): void
    {
        $this->_em->remove($walk);
    }

    public function findById(int $id): ?CollectionContents
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

    public function findBySlug(string $slug): ?CollectionContents
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    public function getMaxPosition(int $collection_id): ?int
    {
        return $this->getOrCreateQueryBuilder()
            ->select('Max(cc.position) AS max_position')
            ->where('cc.collection = :collection')
            ->setParameter('collection', $collection_id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findActivitiesByCollection(int $collection_id)
    {

        $conn = $this->_em->getConnection();
        $sql = 'SELECT a.id, a.name, a.type as activityType, a.short_description AS shortDescription, a.point.ToString() AS point FROM collection_contents cc LEFT JOIN activity a ON cc.activity = a.id WHERE cc.collection = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(0 => $collection_id));
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

    private function selectAllFields(QueryBuilder $qb)
    {
        return $qb->select('cc');
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->_em->createQueryBuilder()
            ->from(CollectionContents::class, 'cc');
    }
}