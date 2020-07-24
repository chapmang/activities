<?php
declare(strict_types=1);
namespace App\Domain\Services;


use App\Domain\Entity\Collection;
use App\Domain\Repository\CollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

final class CollectionServices
{
    /**
     * @var CollectionRepository
     */
    private $collectionRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(CollectionRepository $collectionRepository,
                                EntityManagerInterface $entityManager,
                                PaginatorInterface $paginator)
    {
        $this->collectionRepository = $collectionRepository;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function createCollection(Collection $collection): Collection
    {
        $this->collectionRepository->add($collection);

        $this->entityManager->flush();

        return $collection;
    }

    public function updateCollection(Collection $collection): void
    {
        $this->collectionRepository->add($collection);

        $this->entityManager->flush();

        return;
    }

    public function getCollection($term): ?Collection
    {
        $slug_pattern = '/[a-zA-Z0-9\-_\/]+/';
        if (is_int($term)) {
            return $this->collectionRepository->findById($term);
        } elseif (preg_match($slug_pattern, $term)) {
            return $this->collectionRepository->findBySlug($term);
        }
    }

    public function getActivitiesNotInCollection($searchCollection, $searchTerm)
    {

        return $this->collectionRepository->searchActivitiesNotInCollection($searchCollection, $searchTerm);
    }

    /**
     * @param array $queryData
     * @param int $pageNumber
     * @return PaginationInterface
     */
    public function getPaginatedSearchResults(?array $queryData, int $pageNumber)
    {
        $queryTerm = ($queryData ? $queryData['string'] : null);
        $tags = ($queryData ? $queryData['tags'] : null);

        $queryBuilder = $this->collectionRepository->findAllByNameAndTagsQueryBuilder($queryTerm, $tags);

        return $this->paginator->paginate (
            $queryBuilder, /* query NOT result */
            $pageNumber/*page number*/,
            10/*limit per page*/
        );
    }

    public function recentModifiedCollection($max)
    {
        return $this->collectionRepository->recentModifiedCollection($max);
    }

    public function countCollections(): int
    {
        return $this->collectionRepository->size();
    }

}